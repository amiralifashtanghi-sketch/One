<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class EAFD_Custom_Admin_Custom_Panel {

    public function __construct() {
        add_shortcode( 'eafd_custom_admin', array( __CLASS__, 'render_shortcode' ) );
    }

    private static function check_user_authorized( $user ) {
        if ( ! $user || ! $user->exists() ) {
            return false;
        }
        if ( in_array( 'administrator', (array) $user->roles, true ) || in_array( 'eafd_operator', (array) $user->roles, true ) ) {
            return true;
        }
        return false;
    }

    private static function render_access_denied_box() {
        ?>
        <div style="direction: rtl; font-family: Vazirmatn, sans-serif; text-align: center; max-width: 500px; margin: 80px auto; padding: 30px; background: #fff; border-radius: 16px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #fee2e2;">
            <div style="font-size: 48px; margin-bottom: 15px;">🚫</div>
            <h2 style="color: #ef4444; margin-top: 0; font-weight: 800;">شما اجازه دسترسی به این صفحه را ندارید.</h2>
            <p style="color: #64748b; font-size: 14px; margin-bottom: 25px;">این بخش فقط برای مدیریت و اپراتورهای تعریف‌شدهٔ سایت قابل دسترس است.</p>
            <a href="<?php echo esc_url( wp_logout_url( home_url( '/admin' ) ) ); ?>" class="button" style="background: #ef4444; color: #fff; border: none; padding: 10px 24px; border-radius: 30px; text-decoration: none; font-weight: bold; display: inline-block;">🚪 خروج از حساب کاربری</a>
        </div>
        <?php
    }

    public static function render_shortcode() {
        ob_start();
        if ( ! is_user_logged_in() ) {
            include EAFD_CUSTOM_ADMIN_PATH . 'templates/login-form.php';
        } else {
            $current_user = wp_get_current_user();
            if ( ! self::check_user_authorized( $current_user ) ) {
                self::render_access_denied_box();
            } else {
                $is_admin = current_user_can( 'administrator' );
                $allowed_menus = EAFD_Custom_Admin_Access_Control::get_allowed_menus_for_user( $current_user->ID );
                include EAFD_CUSTOM_ADMIN_PATH . 'templates/panel-main.php';
            }
        }
        return ob_get_clean();
    }

    public static function render_panel() {
        if ( ! is_user_logged_in() ) {
            require_once EAFD_CUSTOM_ADMIN_PATH . 'templates/login-form.php';
            exit;
        }

        $current_user = wp_get_current_user();
        if ( ! self::check_user_authorized( $current_user ) ) {
            self::render_access_denied_box();
            exit;
        }

        $is_admin = current_user_can( 'administrator' );
        $allowed_menus = EAFD_Custom_Admin_Access_Control::get_allowed_menus_for_user( $current_user->ID );

        require_once EAFD_CUSTOM_ADMIN_PATH . 'templates/panel-main.php';
        exit;
    }
}

new EAFD_Custom_Admin_Custom_Panel();
