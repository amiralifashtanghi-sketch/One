<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Custom_Panel {

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
