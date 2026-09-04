<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Rewrite {

    public function __construct() {
        add_action( 'init', array( __CLASS__, 'add_rewrite_rules' ) );
        add_filter( 'query_vars', array( $this, 'register_query_vars' ) );
        add_action( 'template_redirect', array( $this, 'handle_panel_route' ) );
    }

    public static function add_rewrite_rules() {
        add_rewrite_rule( '^admin/?$', 'index.php?eafd_custom_admin_page=1', 'top' );
    }

    public function register_query_vars( $vars ) {
        $vars[] = 'eafd_custom_admin_page';
        return $vars;
    }

    public function handle_panel_route() {
        if ( get_query_var( 'eafd_custom_admin_page' ) ) {
            require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-custom-panel.php';
            EAFD_Custom_Admin_Custom_Panel::render_panel();
            exit;
        }
    }
}

new EAFD_Custom_Admin_Rewrite();
