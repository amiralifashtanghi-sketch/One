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
    }

    public function override_woocommerce_templates($template, $template_name, $template_path) {
        $plugin_path = EAFD_WC_DESIGN_PATH . 'templates/';
        if (file_exists($plugin_path . $template_name)) {
            return $plugin_path . $template_name;
        }
        return $template;
    }
}
EAFD_WooCommerce_Templates::get_instance();
