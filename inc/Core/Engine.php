<?php
namespace SILE\Core;

if (!defined('ABSPATH')) {
    exit;
}

class Engine {
    private $settings;

    public function __construct() {
        $this->settings = get_option('sile_settings', []);

        if (isset($this->settings['enabled']) && $this->settings['enabled'] === 'no') {
            return;
        }

        add_action('template_redirect', [$this, 'start_buffer']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);

        // Elementor Specific Compatibility
        add_action('elementor/frontend/after_enqueue_scripts', [$this, 'elementor_compat']);
    }

    public function elementor_compat() {
        // Force Elementor to use eager loading for first images if needed,
        // but our Buffer already handles most cases.
        // We can add specific filters here to modify Elementor widget options.
        add_filter('elementor/widget/render_content', function($content, $widget) {
            // Further refine Elementor specific widget output if necessary
            return $content;
        }, 10, 2);
    }

    public function start_buffer() {
        if (is_admin() || $this->is_json_request()) {
            return;
        }
        ob_start([$this, 'process_output']);
    }

    private function is_json_request() {
        return (defined('DOING_AJAX') && DOING_AJAX) ||
               (defined('REST_REQUEST') && REST_REQUEST) ||
               wp_is_json_request();
    }

    public function process_output($html) {
        if (empty($html)) {
            return $html;
        }

        // Process images
        $html = $this->parse_images($html);

        // Process background images in inline styles and dynamic CSS
        if ($this->settings['enable_bg_images'] === 'yes') {
            $html = $this->parse_background_images($html);
            $html = $this->parse_style_blocks($html);
        }

        return $html;
    }

    private function parse_images($html) {
        $pattern = '/<img([^>]+)>/i';

        return preg_replace_callback($pattern, function($matches) {
            return $this->optimize_image_tag($matches[0], $matches[1]);
        }, $html);
    }

    private function parse_style_blocks($html) {
        // Find <style> blocks (Elementor often uses these)
        $pattern = '/<style([^>]*)>(.*?)<\/style>/is';

        return preg_replace_callback($pattern, function($matches) {
            $attr = $matches[1];
            $css = $matches[2];

            // Look for background-image: url(...)
            if (strpos($css, 'background-image') !== false) {
                // For each rule with background-image, we want to extract the selector
                // and move the image loading to JS. This is complex for a simple regex.
                // As a premium strategy, we'll replace the url with a tiny placeholder
                // and store the mapping.

                $css = preg_replace_callback('/([^{}]+)\s*\{\s*[^}]*background-image\s*:\s*url\((["\']?)([^"\')]+)\2\)[^}]*\}/i', function($css_matches) {
                    $selector = trim($css_matches[1]);
                    $url = $css_matches[3];

                    // We need a way to tell JS which element this applies to.
                    // This is tricky without a full CSS parser.
                    // For now, we focus on high-fidelity inline styles and Elementor-specific attributes.
                    return $css_matches[0];
                }, $css);
            }

            return "<style$attr>$css</style>";
        }, $html);
    }

    private function parse_background_images($html) {
        // Find elements with style="background-image: url(...)"
        $pattern = '/<([a-z0-9]+)([^>]+style=["\'][^"\']*background-image\s*:\s*url\((["\']?)([^"\')]+)\3\)[^"\']*["\'])/i';

        return preg_replace_callback($pattern, function($matches) {
            $tag = $matches[1];
            $attributes = $matches[2];
            $url = $matches[4];

            // Remove real background-image from style and put in data attribute
            $new_attributes = preg_replace('/background-image\s*:\s*url\([^)]+\)\s*;?/i', '', $attributes);
            $new_attributes .= ' data-sile-bg-image="yes" data-sile-bg-url="' . esc_url($url) . '"';

            if (strpos($new_attributes, 'class=') !== false) {
                $new_attributes = str_replace('class="', 'class="sile-bg-image ', $new_attributes);
                $new_attributes = str_replace("class='", "class='sile-bg-image ", $new_attributes);
            } else {
                $new_attributes .= ' class="sile-bg-image"';
            }

            return "<$tag$new_attributes>";
        }, $html);
    }

    private function optimize_image_tag($full_tag, $attributes) {
        if (strpos($attributes, 'data-sile-ignore') !== false || strpos($attributes, 'data-sile-src') !== false) {
            return $full_tag;
        }

        // Extract SRC
        preg_match('/src=["\']([^"\']+)["\']/i', $attributes, $src_match);
        if (empty($src_match[1]) || strpos($src_match[1], 'data:image') === 0) {
            return $full_tag;
        }

        $src = $src_match[1];
        $priority = $this->determine_priority($full_tag, $attributes, $src);

        // If P1 (High Priority), we don't lazy load, but we still might want to handle it for async decoding
        if ($priority === 'P1') {
            return str_replace('<img ', '<img data-sile-priority="P1" fetchpriority="high" loading="eager" ', $full_tag);
        }

        // Replace src and srcset for lazy loading
        $optimized_tag = str_replace(' src=', ' data-sile-src=', $full_tag);
        if (strpos($optimized_tag, ' srcset=') !== false) {
            $optimized_tag = str_replace(' srcset=', ' data-sile-srcset=', $optimized_tag);
        }

        // Add SILE class and dimension reservation
        $optimized_tag = $this->inject_dimensions($optimized_tag, $attributes);
        $optimized_tag = $this->add_sile_class($optimized_tag);

        return $optimized_tag;
    }

    private function determine_priority($tag, $attr, $src) {
        // 1. Explicit High Priority (fetchpriority, loading eager)
        if (strpos($attr, 'fetchpriority="high"') !== false || strpos($attr, 'loading="eager"') !== false) {
            return 'P1';
        }

        // 2. Logo Detection
        if (preg_match('/(logo|brand|site-title|site-logo)/i', $attr) || strpos($src, 'logo') !== false) {
            return 'P1';
        }

        // 3. Featured Image / Hero (WordPress & Elementor common patterns)
        if (preg_match('/(wp-post-image|attachment-post-thumbnail|hero|banner|elementor-image-featured)/i', $attr)) {
            return 'P1';
        }

        // 4. WooCommerce Main Product Image
        if (strpos($attr, 'woocommerce-main-image') !== false) {
            return 'P1';
        }

        return 'P3'; // Default
    }

    private function inject_dimensions($tag, $attr) {
        if (strpos($attr, 'width=') !== false && strpos($attr, 'height=') !== false) {
            return $tag;
        }

        preg_match('/data-sile-src=["\']([^"\']+)["\']/i', $tag, $src_match);
        if (empty($src_match[1])) return $tag;

        $dimensions = \SILE\Helpers\ImageHelper::get_dimensions_by_url($src_match[1]);

        if ($dimensions) {
            $tag = str_replace('<img ', sprintf('<img width="%d" height="%d" ', $dimensions['width'], $dimensions['height']), $tag);
            // Also inject aspect-ratio for modern CSS
            $style = sprintf('aspect-ratio: %d / %d;', $dimensions['width'], $dimensions['height']);
            if (strpos($tag, 'style=') !== false) {
                $tag = str_replace('style="', 'style="' . $style . ' ', $tag);
                $tag = str_replace("style='", "style='" . $style . ' ', $tag);
            } else {
                $tag = str_replace('<img ', '<img style="' . $style . '" ', $tag);
            }
        }

        return $tag;
    }

    private function add_sile_class($tag) {
        if (strpos($tag, 'class=') !== false) {
            $tag = str_replace('class="', 'class="sile-image ', $tag);
            $tag = str_replace("class='", "class='sile-image ", $tag);
        } else {
            $tag = str_replace('<img ', '<img class="sile-image" ', $tag);
        }
        return $tag;
    }

    public function enqueue_assets() {
        wp_enqueue_style('sile-style', SILE_PLUGIN_URL . 'assets/css/sile-style.css', [], SILE_VERSION);
        wp_enqueue_script('sile-engine', SILE_PLUGIN_URL . 'assets/js/sile-engine.js', [], SILE_VERSION, true);

        wp_localize_script('sile-engine', 'sileVars', [
            'settings' => $this->settings,
            'debug' => current_user_can('manage_options') && ($this->settings['debug_mode'] === 'yes')
        ]);
    }
}
