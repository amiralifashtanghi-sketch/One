<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_WooCommerce_Templates {

    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_filter('woocommerce_locate_template', array($this, 'override_woocommerce_templates'), 99, 3);
        add_filter('wc_get_template', array($this, 'override_wc_get_template'), 99, 5);
        add_filter('comments_template', array($this, 'override_product_reviews_template'), 99);
    }

    public function override_woocommerce_templates($template, $template_name, $template_path) {
        $plugin_template = EAFD_WC_DESIGN_PATH . 'templates/' . $template_name;
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
        return $template;
    }

    public function override_wc_get_template($template, $template_name, $args, $template_path, $default_path) {
        $plugin_template = EAFD_WC_DESIGN_PATH . 'templates/' . $template_name;
        if (file_exists($plugin_template)) {
            return $plugin_template;
        }
        return $template;
    }

    public function override_product_reviews_template($comment_template) {
        if (is_product()) {
            $plugin_reviews = EAFD_WC_DESIGN_PATH . 'templates/single-product-reviews.php';
            if (file_exists($plugin_reviews)) {
                return $plugin_reviews;
            }
        }
        return $comment_template;
    }
}
