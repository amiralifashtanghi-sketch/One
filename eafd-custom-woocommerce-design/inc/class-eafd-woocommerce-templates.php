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
        // High priority template overrides
        add_filter('woocommerce_locate_template', array($this, 'override_woocommerce_templates'), 9999, 3);
        add_filter('wc_get_template', array($this, 'override_wc_get_template'), 9999, 5);
        add_filter('comments_template', array($this, 'override_product_reviews_template'), 9999);
        add_filter('woocommerce_account_menu_items', array($this, 'filter_account_menu_items'), 9999);

        // Shortcode fallbacks
        add_shortcode('eafd_cart', array($this, 'render_cart_shortcode'));
        add_shortcode('eafd_checkout', array($this, 'render_checkout_shortcode'));

        // High priority content filter override for pages using blocks or custom page templates
        add_filter('the_content', array($this, 'override_page_content_templates'), 9999);
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

    public function override_page_content_templates($content) {
        if (is_admin() || !function_exists('is_cart') || !function_exists('is_checkout')) {
            return $content;
        }

        if (is_cart() && !is_wc_endpoint_url('order-received')) {
            if (WC()->cart->is_empty()) {
                return $this->get_template_html('cart/cart-empty.php');
            }
            return $this->get_template_html('cart/cart.php');
        }

        if (is_checkout() && !is_wc_endpoint_url('order-received')) {
            return $this->get_template_html('checkout/form-checkout.php');
        }

        return $content;
    }

    public function render_cart_shortcode() {
        if (WC()->cart->is_empty()) {
            return $this->get_template_html('cart/cart-empty.php');
        }
        return $this->get_template_html('cart/cart.php');
    }

    public function render_checkout_shortcode() {
        return $this->get_template_html('checkout/form-checkout.php');
    }

    private function get_template_html($template_name) {
        $file = EAFD_WC_DESIGN_PATH . 'templates/' . $template_name;
        if (!file_exists($file)) {
            return '';
        }
        ob_start();
        $checkout = WC()->checkout();
        include $file;
        return ob_get_clean();
    }
}
