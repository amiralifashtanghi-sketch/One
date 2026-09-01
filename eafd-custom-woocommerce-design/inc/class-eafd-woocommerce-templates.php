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
        add_filter('woocommerce_account_menu_items', array($this, 'filter_account_menu_items'), 99);
    }

    public function filter_account_menu_items($items) {
        $options = EAFD_Admin_Settings::get_instance()->get_options();
        if (!empty($options['menu_dashboard_disable'])) {
            unset($items['dashboard']);
        }
        if (!empty($options['menu_orders_disable'])) {
            unset($items['orders']);
        }
        if (!empty($options['menu_downloads_disable'])) {
            unset($items['downloads']);
        }
        if (!empty($options['menu_edit_address_disable'])) {
            unset($items['edit-address']);
        }
        if (!empty($options['menu_edit_account_disable'])) {
            unset($items['edit-account']);
        }
        if (!empty($options['menu_logout_disable'])) {
            unset($items['customer-logout']);
        }
        return $items;
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
