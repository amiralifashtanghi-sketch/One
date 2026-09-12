<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Admin_Menu {

    public function __construct() {
        add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
    }

    public function register_admin_menu() {
        add_menu_page(
            'ادمین وردپرس',
            'ادمین وردپرس',
            'manage_options',
            'eafd-custom-admin',
            array( $this, 'render_operators_page' ),
            'dashicons-shield-alt',
            3
        );

        add_submenu_page(
            'eafd-custom-admin',
            'مدیریت اپراتورها',
            'مدیریت اپراتورها',
            'manage_options',
            'eafd-custom-admin',
            array( $this, 'render_operators_page' )
        );

        add_submenu_page(
            'eafd-custom-admin',
            'سطح دسترسی منوها',
            'سطح دسترسی منوها',
            'manage_options',
            'eafd-custom-admin-permissions',
            array( $this, 'render_permissions_page' )
        );

        add_submenu_page(
            'eafd-custom-admin',
            'کنترل ریز صفحات (متاباکس‌ها)',
            'کنترل ریز صفحات',
            'manage_options',
            'eafd-custom-admin-meta',
            array( $this, 'render_meta_page' )
        );
    }

    public function render_operators_page() {
        require_once EAFD_CUSTOM_ADMIN_PATH . 'templates/admin-operators.php';
    }

    public function render_permissions_page() {
        require_once EAFD_CUSTOM_ADMIN_PATH . 'templates/admin-permissions.php';
    }

    public function render_meta_page() {
        require_once EAFD_CUSTOM_ADMIN_PATH . 'templates/admin-meta.php';
    }
}

new EAFD_Custom_Admin_Admin_Menu();
