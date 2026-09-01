<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_Frontend_Assets {

    private static $instance = null;

    public static function get_instance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        add_action('wp_enqueue_scripts', array($this, 'enqueue_frontend_assets'));
        add_action('wp_head', array($this, 'inject_dynamic_css'), 99);
    }

    public function enqueue_frontend_assets() {
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            return;
        }

        $options = EAFD_Admin_Settings::get_instance()->get_options();

        wp_enqueue_style('eafd-font-vazirmatn', EAFD_WC_DESIGN_URL . 'assets/css/font-vazirmatn.css', array(), EAFD_WC_DESIGN_VERSION);
        wp_enqueue_style('eafd-fontawesome', EAFD_WC_DESIGN_URL . 'assets/css/fontawesome.css', array(), EAFD_WC_DESIGN_VERSION);

        if (!empty($options['enable_theme_reset'])) {
            wp_enqueue_style('eafd-theme-reset', EAFD_WC_DESIGN_URL . 'assets/css/theme-reset.css', array(), EAFD_WC_DESIGN_VERSION);
        }

        wp_enqueue_style('eafd-wc-core', EAFD_WC_DESIGN_URL . 'assets/css/woocommerce-modern-core.css', array(), EAFD_WC_DESIGN_VERSION);
        wp_enqueue_style('eafd-reviews-redesign', EAFD_WC_DESIGN_URL . 'assets/css/reviews-redesign.css', array(), EAFD_WC_DESIGN_VERSION);

        if (!empty($options['mobile_bottom_nav'])) {
            wp_enqueue_style('eafd-mobile-bottom-nav', EAFD_WC_DESIGN_URL . 'assets/css/mobile-bottom-nav.css', array(), EAFD_WC_DESIGN_VERSION);
            add_action('wp_footer', array($this, 'render_mobile_bottom_nav'));
        }

        if (!empty($options['mobile_side_drawer'])) {
            wp_enqueue_style('eafd-mobile-side-drawer', EAFD_WC_DESIGN_URL . 'assets/css/mobile-side-drawer.css', array(), EAFD_WC_DESIGN_VERSION);
            add_action('wp_footer', array($this, 'render_mobile_side_drawer'));
        }

        wp_enqueue_script('eafd-wc-script', EAFD_WC_DESIGN_URL . 'assets/js/woocommerce-modern.js', array('jquery'), EAFD_WC_DESIGN_VERSION, true);
    }

    public function render_mobile_side_drawer() {
        if (!is_account_page() || !is_user_logged_in()) {
            return;
        }
        ?>
        <div class="eafd-mobile-toggle-btn" id="eafdMobileToggleBtn">
            <i class="fas fa-bars"></i>
        </div>
        <div class="eafd-mobile-drawer-overlay" id="eafdMobileOverlay"></div>
        <div class="eafd-mobile-drawer" id="eafdMobileDrawer">
            <h3 style="font-size: 18px; font-weight: 800; color: var(--blue-primary); margin-bottom: 20px;">منوی حساب کاربری</h3>
            <?php
            if (has_nav_menu('my-account') || is_account_page()) {
                wc_get_template('myaccount/navigation.php');
            }
            ?>
        </div>
        <?php
    }

    public function render_mobile_bottom_nav() {
        if (!is_account_page() || !is_user_logged_in()) {
            return;
        }
        $cart_url = function_exists('wc_get_cart_url') ? wc_get_cart_url() : '#';
        $account_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : '#';
        $shop_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('shop') : '#';
        ?>
        <div class="eafd-mobile-bottom-nav">
            <a href="<?php echo esc_url($shop_url); ?>" class="eafd-bottom-nav-item">
                <i class="fas fa-store"></i>
                <span>فروشگاه</span>
            </a>
            <a href="<?php echo esc_url($cart_url); ?>" class="eafd-bottom-nav-item">
                <i class="fas fa-shopping-cart"></i>
                <span>سبد خرید</span>
            </a>
            <a href="<?php echo esc_url($account_url); ?>" class="eafd-bottom-nav-item active">
                <i class="fas fa-user"></i>
                <span>حساب من</span>
            </a>
        </div>
        <?php
    }

    public function inject_dynamic_css() {
        if (!is_woocommerce() && !is_cart() && !is_checkout() && !is_account_page()) {
            return;
        }

        $options = EAFD_Admin_Settings::get_instance()->get_options();

        $primary   = esc_attr($options['color_primary']);
        $secondary = esc_attr($options['color_secondary']);
        $accent    = esc_attr($options['color_accent']);
        $bg_opacity = esc_attr($options['glass_bg_opacity']);
        $blur       = esc_attr($options['glass_blur']) . 'px';
        $neo_radius = esc_attr($options['neo_radius']) . 'px';

        echo '<style id="eafd-wc-dynamic-css">
        :root {
            --blue-primary: ' . $primary . ';
            --turquoise: ' . $secondary . ';
            --orange: ' . $accent . ';
            --glass-bg: rgba(255, 255, 255, ' . $bg_opacity . ');
            --glass-blur: ' . $blur . ';
            --neo-bg: #ecf0f1;
            --neo-light: #ffffff;
            --neo-dark: #bdc3c7;
            --neo-radius: ' . $neo_radius . ';
            --font: "Vazirmatn", sans-serif;
            --shadow-neo-out: 6px 6px 16px #bdc3c7, -6px -6px 16px #ffffff;
            --shadow-neo-in: inset 4px 4px 10px #bdc3c7, inset -4px -4px 10px #ffffff;
        }
        </style>';
    }
}
EAFD_Frontend_Assets::get_instance();
