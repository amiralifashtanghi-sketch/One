<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_WC_Design {

    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('plugins_loaded', array($this, 'init'));
    }

    public function init() {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array($this, 'woocommerce_missing_notice'));
            return;
        }

        $this->includes();
        $this->hooks();
    }

    public function woocommerce_missing_notice() {
        echo '<div class="error"><p>';
        echo sprintf(
            esc_html__('افزونه "طراحی اختصاصی ووکامرس ساخته شده eafd.ir" نیازمند نصب و فعال‌سازی افزونه ووکامرس می‌باشد.', 'eafd-custom-wc')
        );
        echo '</p></div>';
    }

    private function includes() {
        require_once EAFD_WC_DESIGN_PATH . 'inc/class-eafd-admin-settings.php';
        require_once EAFD_WC_DESIGN_PATH . 'inc/class-eafd-frontend-assets.php';
        require_once EAFD_WC_DESIGN_PATH . 'inc/class-eafd-woocommerce-templates.php';
    }

    private function hooks() {
        // Core initialization hooks will be added here
    }
}
