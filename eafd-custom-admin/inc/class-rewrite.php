<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Rewrite {

    public function __construct() {
        add_action( 'init', array( __CLASS__, 'add_rewrite_rules' ) );
        add_filter( 'query_vars', array( $this, 'register_query_vars' ) );
        add_action( 'parse_request', array( $this, 'intercept_admin_url' ), 1 );
        add_action( 'template_redirect', array( $this, 'handle_panel_route' ) );
    }

    public static function add_rewrite_rules() {
        add_rewrite_rule( '^admin/?$', 'index.php?eafd_custom_admin_page=1', 'top' );
    }

    public function register_query_vars( $vars ) {
        $vars[] = 'eafd_custom_admin_page';
        return $vars;
    }

    /**
     * Intercept URI directly to avoid 404 errors even if rewrite rules are not flushed or pretty permalinks disabled
     */
    public function intercept_admin_url( $wp ) {
        $req_uri = $_SERVER['REQUEST_URI'] ?? '';
        $path = trim( parse_url( $req_uri, PHP_URL_PATH ) ?? '', '/' );

        $home_path = trim( parse_url( home_url(), PHP_URL_PATH ) ?? '', '/' );
        if ( ! empty( $home_path ) && strpos( $path, $home_path ) === 0 ) {
            $path = trim( substr( $path, strlen( $home_path ) ), '/' );
        }

        if ( $path === 'admin' || $path === 'index.php/admin' ) {
            set_query_var( 'eafd_custom_admin_page', '1' );
        }
    }

    public function handle_panel_route() {
        if ( get_query_var( 'eafd_custom_admin_page' ) ) {
            status_header( 200 );
            require_once EAFD_CUSTOM_ADMIN_PATH . 'inc/class-custom-panel.php';
            EAFD_Custom_Admin_Custom_Panel::render_panel();
            exit;
        }
    }
}

new EAFD_Custom_Admin_Rewrite();
