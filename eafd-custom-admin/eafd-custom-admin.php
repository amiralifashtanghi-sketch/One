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
     * Create or retrieve automatic admin page
     */
    public static function create_admin_page() {
        $page_id = get_option( 'eafd_custom_admin_page_id' );

        if ( $page_id && ( $post = get_post( $page_id ) ) ) {
            if ( strpos( $post->post_content, '[eafd_custom_admin]' ) === false ) {
                wp_update_post( array(
                    'ID'           => $page_id,
                    'post_content' => '[eafd_custom_admin]'
                ) );
            }
            return $page_id;
        }

        // Check by path 'admin'
        $existing_page = get_page_by_path( 'admin' );
        if ( $existing_page ) {
            update_option( 'eafd_custom_admin_page_id', $existing_page->ID );
            if ( strpos( $existing_page->post_content, '[eafd_custom_admin]' ) === false ) {
                wp_update_post( array(
                    'ID'           => $existing_page->ID,
                    'post_content' => '[eafd_custom_admin]'
                ) );
            }
            return $existing_page->ID;
        }

        // Create page
        $page_data = array(
            'post_title'     => 'ادمین اختصاصی',
            'post_name'      => 'admin',
            'post_content'   => '[eafd_custom_admin]',
            'post_status'    => 'publish',
            'post_type'      => 'page',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
        );

        $new_page_id = wp_insert_post( $page_data );
        if ( $new_page_id && ! is_wp_error( $new_page_id ) ) {
            update_option( 'eafd_custom_admin_page_id', $new_page_id );
            return $new_page_id;
        }

        return false;
    }

    /**
     * On Plugin Activation
     */
    public static function activate() {
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-roles.php';
        require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-rewrite.php';
        EAFD_Custom_Admin_Roles::add_roles();
        self::create_admin_page();
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
