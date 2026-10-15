<?php
/**
 * Plugin Name: افزونه ورود پیامکی و اطلاع‌رسانی ووکامرس EAFD
 * Plugin URI: https://eafd.ir
 * Description: افزونه جامع ورود پیامکی، احراز هویت هوشمند، پاپ‌آپ واریفای تسویه حساب ووکامرس و اطلاع‌رسانی پیامکی با SMS.ir
 * Version: 1.0.0
 * Author: EAFD.ir
 * Author URI: https://eafd.ir
 * Text Domain: eafd-sms-login
 * Domain Path: /languages
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Define Plugin Constants
define('EAFD_SMS_VERSION', '1.0.0');
define('EAFD_SMS_PATH', plugin_dir_path(__FILE__));
define('EAFD_SMS_URL', plugin_dir_url(__FILE__));
define('EAFD_SMS_BASENAME', plugin_basename(__FILE__));

/**
 * Main Plugin Class
 */
final class EAFD_SMS_Login {

    private static $instance = null;

    public static function get_instance() {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->includes();
        $this->init_hooks();
    }

    private function includes() {
        require_once EAFD_SMS_PATH . 'includes/class-eafd-phone-helper.php';
        require_once EAFD_SMS_PATH . 'includes/class-eafd-sms-logger.php';
        require_once EAFD_SMS_PATH . 'includes/class-eafd-sms-client.php';
        require_once EAFD_SMS_PATH . 'includes/class-eafd-admin-settings.php';
        require_once EAFD_SMS_PATH . 'includes/class-eafd-auth.php';
        require_once EAFD_SMS_PATH . 'includes/class-eafd-checkout.php';
        require_once EAFD_SMS_PATH . 'includes/class-eafd-wc-notifications.php';
        require_once EAFD_SMS_PATH . 'includes/class-eafd-export.php';
    }

    private function init_hooks() {
        add_action('plugins_loaded', array($this, 'load_textdomain'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }

    public function load_textdomain() {
        load_plugin_textdomain('eafd-sms-login', false, dirname(EAFD_SMS_BASENAME) . '/languages');
    }

    public function enqueue_frontend_assets() {
        wp_enqueue_style('eafd-sms-frontend', EAFD_SMS_URL . 'assets/css/frontend.css', array(), EAFD_SMS_VERSION);
        wp_enqueue_script('eafd-sms-frontend', EAFD_SMS_URL . 'assets/js/frontend.js', array('jquery'), EAFD_SMS_VERSION, true);

        wp_localize_script('eafd-sms-frontend', 'eafd_sms_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eafd_sms_nonce'),
            'is_user_logged_in' => is_user_logged_in(),
            'site_title' => get_bloginfo('name')
        ));
    }

    public function enqueue_admin_assets($hook) {
        if (strpos($hook, 'eafd-sms') !== false) {
            wp_enqueue_style('eafd-sms-admin', EAFD_SMS_URL . 'assets/css/admin.css', array(), EAFD_SMS_VERSION);
            wp_enqueue_script('eafd-sms-admin', EAFD_SMS_URL . 'assets/js/admin.js', array('jquery'), EAFD_SMS_VERSION, true);
        }
    }
}

function EAFD_SMS() {
    return EAFD_SMS_Login::get_instance();
}

// Global initialization hook
add_action('plugins_loaded', 'EAFD_SMS', 10);
