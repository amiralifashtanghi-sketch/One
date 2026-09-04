<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_Checkout {

    public function __construct() {
        // Make billing_email optional in WooCommerce checkout
        add_filter('woocommerce_billing_fields', array($this, 'make_email_optional'), 999);
        add_filter('woocommerce_checkout_fields', array($this, 'customize_checkout_fields'), 999);

        // Checkout Verify Modal for Guest Users
        add_action('wp_enqueue_scripts', array($this, 'enqueue_checkout_scripts'));
        add_action('wp_footer', array($this, 'render_checkout_verify_modal'));

        // Checkout Validation Hook
        add_action('woocommerce_checkout_process', array($this, 'validate_checkout_phone_verification'));

        // AJAX Checkout OTP verification
        add_action('wp_ajax_nopriv_eafd_checkout_send_otp', array($this, 'ajax_checkout_send_otp'));
        add_action('wp_ajax_eafd_checkout_send_otp', array($this, 'ajax_checkout_send_otp'));

        add_action('wp_ajax_nopriv_eafd_checkout_verify_otp', array($this, 'ajax_checkout_verify_otp'));
        add_action('wp_ajax_eafd_checkout_verify_otp', array($this, 'ajax_checkout_verify_otp'));
    }

    public function make_email_optional($fields) {
        $settings = get_option('eafd_sms_settings', []);
        if (!empty($settings['disable_email_req'])) {
            if (isset($fields['billing_email'])) {
                $fields['billing_email']['required'] = false;
            }
        }
        return $fields;
    }

    public function customize_checkout_fields($fields) {
        $settings = get_option('eafd_sms_settings', []);
        if (!empty($settings['disable_email_req'])) {
            if (isset($fields['billing']['billing_email'])) {
                $fields['billing']['billing_email']['required'] = false;
            }
        }
        return $fields;
    }

    public function enqueue_checkout_scripts() {
        if (is_checkout()) {
            wp_enqueue_style('eafd-sms-frontend', EAFD_SMS_URL . 'assets/css/frontend.css', array(), EAFD_SMS_VERSION);
            wp_enqueue_script('eafd-checkout-js', EAFD_SMS_URL . 'assets/js/checkout.js', array('jquery', 'wc-checkout'), EAFD_SMS_VERSION, true);

            wp_localize_script('eafd-checkout-js', 'eafd_checkout_obj', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('eafd_sms_nonce'),
                'is_user_logged_in' => is_user_logged_in() ? 1 : 0,
                'checkout_verify_enabled' => get_option('eafd_sms_settings')['enable_wc_checkout_verify'] ?? 1
            ));
        }
    }

    public function render_checkout_verify_modal() {
        if (is_user_logged_in()) {
            return;
        }

        $settings = get_option('eafd_sms_settings', []);
        if (empty($settings['enable_wc_checkout_verify'])) {
            return;
        }
        ?>
        <div id="eafd-checkout-modal-overlay" class="eafd-modal-overlay" style="display:none;">
            <div class="eafd-modal-content">
                <button type="button" class="eafd-modal-close" id="eafd-modal-close">&times;</button>
                <div class="eafd-modal-header">
                    <h3>تایید شماره تلفن همراه</h3>
                    <p>جهت ثبت و پردازش سفارش، شماره همراه خود را تایید نمایید.</p>
                </div>

                <div class="eafd-step" id="eafd-checkout-step-otp">
                    <p class="eafd-otp-msg">کد ۴ رقمی ارسال شده به شماره <strong id="eafd-checkout-target-phone"></strong> را وارد کنید:</p>
                    <div class="eafd-otp-inputs" dir="ltr">
                        <input type="text" maxlength="1" class="eafd-checkout-otp-digit" data-idx="1" autofocus />
                        <input type="text" maxlength="1" class="eafd-checkout-otp-digit" data-idx="2" />
                        <input type="text" maxlength="1" class="eafd-checkout-otp-digit" data-idx="3" />
                        <input type="text" maxlength="1" class="eafd-checkout-otp-digit" data-idx="4" />
                    </div>
                    <button type="button" class="eafd-btn eafd-btn-primary" id="eafd-btn-checkout-verify-otp">تایید کد و ثبت سفارش</button>
                    <input type="hidden" id="eafd_checkout_verified_token" name="eafd_checkout_verified_token" value="" />
                </div>
                <div class="eafd-brand-footer">طراحی شده با EAFD.IR</div>
            </div>
        </div>
        <?php
    }

    public function validate_checkout_phone_verification() {
        if (is_user_logged_in()) {
            return;
        }

        $settings = get_option('eafd_sms_settings', []);
        if (empty($settings['enable_wc_checkout_verify'])) {
            return;
        }

        $billing_phone = isset($_POST['billing_phone']) ? sanitize_text_field($_POST['billing_phone']) : '';
        $normalized = EAFD_Phone_Helper::normalize_phone($billing_phone);

        if (!$normalized) {
            wc_add_notice('لطفاً یک شماره تلفن همراه معتبر وارد کنید.', 'error');
            return;
        }

        $verified_phone = WC()->session->get('eafd_verified_checkout_phone');

        if ($verified_phone !== $normalized) {
            wc_add_notice('شماره تلفن همراه شما هنوز تایید نشده است. لطفاً کد تایید پیامکی را وارد نمایید.', 'error');
        }
    }

    public function ajax_checkout_send_otp() {
        check_ajax_referer('eafd_sms_nonce', 'nonce');

        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $normalized = EAFD_Phone_Helper::normalize_phone($phone);

        if (!$normalized) {
            wp_send_json_error(['message' => 'شماره همراه معتبر نیست.']);
        }

        $transient_key = 'eafd_checkout_otp_' . $normalized;
        $code = sprintf('%04d', rand(1000, 9999));

        set_transient($transient_key, [
            'code' => $code,
            'time' => time()
        ], 300);

        $settings = get_option('eafd_sms_settings', []);
        $template_id = $settings['otp_template_id'] ?? '';
        $var_code = !empty($settings['otp_var_code']) ? $settings['otp_var_code'] : 'CODE';
        $var_site = !empty($settings['otp_var_site']) ? $settings['otp_var_site'] : 'SITE';

        $client = new EAFD_SMS_Client();
        $res = $client->send_pattern($normalized, $template_id, [
            $var_code => $code,
            $var_site => get_bloginfo('name')
        ]);

        if ($res['success']) {
            wp_send_json_success(['message' => 'کد تایید ارسال شد.', 'phone' => $normalized]);
        } else {
            wp_send_json_error(['message' => $res['message']]);
        }
    }

    public function ajax_checkout_verify_otp() {
        check_ajax_referer('eafd_sms_nonce', 'nonce');

        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $code = isset($_POST['code']) ? sanitize_text_field($_POST['code']) : '';

        $normalized = EAFD_Phone_Helper::normalize_phone($phone);
        if (!$normalized) {
            wp_send_json_error(['message' => 'شماره همراه معتبر نیست.']);
        }

        $transient_key = 'eafd_checkout_otp_' . $normalized;
        $stored = get_transient($transient_key);

        if (!$stored || !isset($stored['code']) || $stored['code'] !== $code) {
            wp_send_json_error(['message' => 'کد تایید وارد شده نادرست است.']);
        }

        delete_transient($transient_key);

        if (WC()->session) {
            WC()->session->set('eafd_verified_checkout_phone', $normalized);
        }

        wp_send_json_success(['message' => 'شماره همراه با موفقیت تایید شد.']);
    }
}

new EAFD_Checkout();
