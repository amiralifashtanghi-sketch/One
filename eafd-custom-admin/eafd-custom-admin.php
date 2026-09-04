<?php
/**
 * Plugin Name: ساختن ادمین وردپرس ساخته شده توسط eafd.ir
 * Plugin URI: https://eafd.ir
 * Description: افزونه مدیریت اختصاصی، سبک و موبایلی وردپرس با کنترل دقیق دسترسی منوها، زیرمنوها و بخش‌های صفحات.
 * Version: 1.0.0
 * Author: eafd.ir
 * Author URI: https://eafd.ir
 * Text Domain: eafd-custom-admin
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

define( 'EAFD_CUSTOM_ADMIN_VERSION', '1.0.0' );
define( 'EAFD_CUSTOM_ADMIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'EAFD_CUSTOM_ADMIN_URL', plugin_dir_url( __FILE__ ) );

/**
 * Main Plugin Class
 */
final class EAFD_Custom_Admin {

    /**
     * Singleton Instance
     * @var EAFD_Custom_Admin|null
     */
    private static $instance = null;

    /**
     * Get Instance
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     */
    private function __construct() {
        $this->includes();
    }

    /**
     * Include required files
     */
    private function includes() {
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-roles.php';
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-admin-menu.php';
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-access-control.php';
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-rewrite.php';
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-login.php';
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-custom-panel.php';
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-meta-hider.php';
    }

    /**
     * On Plugin Activation
     */
    public static function activate() {
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-roles.php';
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-rewrite.php';
        EAFD_Custom_Admin_Roles::add_roles();
        EAFD_Custom_Admin_Rewrite::add_rewrite_rules();
        flush_rewrite_rules();
    }

    /**
     * On Plugin Deactivation
     */
    public static function deactivate() {
        flush_rewrite_rules();
    }
}

// Register Activation Hooks at Top Level
register_activation_hook( __FILE__, array( 'EAFD_Custom_Admin', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'EAFD_Custom_Admin', 'deactivate' ) );

/**
 * Main instance initialization function
 */
function eafd_custom_admin() {
    return EAFD_Custom_Admin::get_instance();
}

// Initialize when plugins are loaded
add_action( 'plugins_loaded', 'eafd_custom_admin' );
