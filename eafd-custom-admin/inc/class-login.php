<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Login {

    public function __construct() {
        add_action( 'wp_ajax_nopriv_eafd_custom_admin_login', array( $this, 'ajax_login' ) );
        add_action( 'wp_ajax_eafd_custom_admin_login', array( $this, 'ajax_login' ) );
    }

    public static function is_user_logged_in() {
        return is_user_logged_in();
    }

    public function ajax_login() {
        check_ajax_referer( 'eafd_login_nonce', 'security' );

        $phone = sanitize_text_field( $_POST['phone'] ?? '' );
        $password = $_POST['password'] ?? ''; // Preserve special characters in passwords

        if ( empty( $phone ) || empty( $password ) ) {
            wp_send_json_error( array( 'message' => 'لطفاً شماره موبایل و رمز عبور را وارد کنید.' ) );
        }

        $creds = array(
            'user_login'    => $phone,
            'user_password' => $password,
            'remember'      => true,
        );

        $user = wp_signon( $creds, false );

        if ( is_wp_error( $user ) ) {
            wp_send_json_error( array( 'message' => 'شماره موبایل یا رمز عبور اشتباه است.' ) );
        }

        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID, true );

        wp_send_json_success( array(
            'message'      => 'ورود با موفقیت انجام شد. در حال انتقال...',
            'redirect_url' => home_url( '/admin' )
        ) );
    }
}

new EAFD_Custom_Admin_Login();
