<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Custom_Panel {

    public function __construct() {
        add_shortcode( 'eafd_custom_admin', array( __CLASS__, 'render_shortcode' ) );
    }

    public static function render_shortcode() {
        ob_start();
        if ( ! is_user_logged_in() ) {
            include EAFD_CUSTOM_ADMIN_PATH . 'templates/login-form.php';
        } else {
            $current_user = wp_get_current_user();
            $is_admin = current_user_can( 'administrator' );
            $allowed_menus = EAFD_Custom_Admin_Access_Control::get_allowed_menus_for_user( $current_user->ID );

            include EAFD_CUSTOM_ADMIN_PATH . 'templates/panel-main.php';
        }
        return ob_get_clean();
    }

    public static function render_panel() {
        if ( ! is_user_logged_in() ) {
            require_once EAFD_CUSTOM_ADMIN_PATH . 'templates/login-form.php';
            exit;
        }

        $current_user = wp_get_current_user();
        $is_admin = current_user_can( 'administrator' );
        $allowed_menus = EAFD_Custom_Admin_Access_Control::get_allowed_menus_for_user( $current_user->ID );

        require_once EAFD_CUSTOM_ADMIN_PATH . 'templates/panel-main.php';
        exit;
    }
}

new EAFD_Custom_Admin_Custom_Panel();
