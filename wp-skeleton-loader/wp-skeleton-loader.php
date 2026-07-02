<?php
/**
 * Plugin Name: WP Ultra Fast Skeleton Loader
 * Description: Replaces images with skeleton placeholders and loads them lazily to stop the browser loading spinner early.
 * Version: 1.1.0
 * Author: James (AI Engineer)
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit;
}

class WP_Skeleton_Loader {

    private static $instance = null;
    private $processed_count = 0;
    private $exclude_count = 2; // Number of initial images to exclude

    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_assets']);

        $filters = [
            'the_content',
            'post_thumbnail_html',
            'widget_text',
            'elementor/frontend/the_content'
        ];

        foreach ($filters as $filter) {
            add_filter($filter, [$this, 'process_images'], 100);
        }
    }

    public function enqueue_assets() {
        wp_enqueue_style('wp-skeleton-loader-css', plugin_dir_url(__FILE__) . 'assets/css/skeleton.css', [], '1.1.0');
        wp_enqueue_script('wp-skeleton-loader-js', plugin_dir_url(__FILE__) . 'assets/js/skeleton.js', [], '1.1.0', true);
    }

    public function process_images($content) {
        if (is_feed() || is_preview() || is_admin()) {
            return $content;
        }

        // Use a more robust regex to match img tags
        return preg_replace_callback('/<img\s+([^>]+)>/i', [$this, 'replace_image_callback'], $content);
    }

    private function replace_image_callback($matches) {
        $attributes_str = $matches[1];

        // Skip if it's already processed or has a specific skip class
        if (preg_match('/\b(data-wp-skeleton|no-skeleton)\b/i', $attributes_str)) {
            return $matches[0];
        }

        // Handle exclusion for header images
        if ($this->processed_count < $this->exclude_count) {
            $this->processed_count++;
            return $matches[0];
        }
        $this->processed_count++;

        // Extract attributes
        $attrs = $this->parse_attributes($attributes_str);

        // If no src, return original
        if (empty($attrs['src'])) {
            return $matches[0];
        }

        $width = isset($attrs['width']) ? $attrs['width'] : 1;
        $height = isset($attrs['height']) ? $attrs['height'] : 1;

        // Create a transparent SVG placeholder with the same dimensions
        $placeholder = 'data:image/svg+xml,%3Csvg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 ' . $width . ' ' . $height . '"%3E%3C/svg%3E';

        // Swap src and srcset
        $attrs['data-src'] = $attrs['src'];
        $attrs['src'] = $placeholder;

        if (!empty($attrs['srcset'])) {
            $attrs['data-srcset'] = $attrs['srcset'];
            unset($attrs['srcset']);
        }

        if (!empty($attrs['sizes'])) {
            $attrs['data-sizes'] = $attrs['sizes'];
            unset($attrs['sizes']);
        }

        // Add class
        $class = isset($attrs['class']) ? $attrs['class'] : '';
        $attrs['class'] = trim($class . ' wp-skeleton-img');

        $attrs['data-wp-skeleton'] = 'true';

        // Rebuild attributes string
        $new_attributes_str = '';
        foreach ($attrs as $name => $value) {
            $new_attributes_str .= ' ' . $name . '="' . esc_attr($value) . '"';
        }

        return '<img' . $new_attributes_str . '>';
    }

    private function parse_attributes($attributes_str) {
        $attrs = [];
        // Regex to match attributes like name="value" or name='value' or name=value
        $pattern = '/([a-zA-Z0-9\-]+)\s*=\s*(?:([\'"])(.*?)\2|([^\s>]+))/i';

        if (preg_match_all($pattern, $attributes_str, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $name = strtolower($match[1]);
                $value = $match[3] !== '' ? $match[3] : $match[4];
                $attrs[$name] = $value;
            }
        }
        return $attrs;
    }
}

WP_Skeleton_Loader::get_instance();
