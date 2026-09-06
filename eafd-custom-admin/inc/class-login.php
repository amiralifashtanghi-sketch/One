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

    private function normalize_phone( $str ) {
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $arabic  = array('٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩');
        $num     = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
        $str     = str_replace( $persian, $num, $str );
        $str     = str_replace( $arabic, $num, $str );
        return trim( $str );
    }

    public function ajax_login() {
        if ( ! isset( $_POST['security'] ) || ! wp_verify_nonce( $_POST['security'], 'eafd_login_nonce' ) ) {
            if ( ob_get_length() ) { ob_clean(); }
            wp_send_json_error( array( 'message' => 'نشست امنیتی منقضی شده است. لطفاً صفحه را رفرش کرده و دوباره وارد شوید.' ) );
        }

        $phone = $this->normalize_phone( sanitize_text_field( $_POST['phone'] ?? '' ) );
        $password = $_POST['password'] ?? ''; // Preserve special characters in passwords

        if ( empty( $phone ) || empty( $password ) ) {
            if ( ob_get_length() ) { ob_clean(); }
            wp_send_json_error( array( 'message' => 'لطفاً شماره موبایل و رمز عبور را وارد کنید.' ) );
        }

        // Search user by login, email, or meta eafd_phone_number
        $user_obj = get_user_by( 'login', $phone );
        if ( ! $user_obj ) {
            $user_obj = get_user_by( 'email', $phone );
        }
        if ( ! $user_obj ) {
            $matched_users = get_users( array(
                'meta_key'   => 'eafd_phone_number',
                'meta_value' => $phone,
                'number'     => 1
            ) );
            if ( ! empty( $matched_users ) ) {
                $user_obj = $matched_users[0];
            }
        }

        $login_identifier = $user_obj ? $user_obj->user_login : $phone;

        $creds = array(
            'user_login'    => $login_identifier,
            'user_password' => $password,
            'remember'      => true,
        );

        $user = wp_signon( $creds, false );

        if ( is_wp_error( $user ) ) {
            if ( ob_get_length() ) { ob_clean(); }
            wp_send_json_error( array( 'message' => 'شماره موبایل یا رمز عبور اشتباه است.' ) );
        }

        wp_set_current_user( $user->ID );
        wp_set_auth_cookie( $user->ID, true, is_ssl() );

        if ( ob_get_length() ) { ob_clean(); }
        wp_send_json_success( array(
            'message'      => 'ورود با موفقیت انجام شد. در حال انتقال...',
            'redirect_url' => home_url( '/admin' )
        ) );
    }
}

new EAFD_Custom_Admin_Login();
