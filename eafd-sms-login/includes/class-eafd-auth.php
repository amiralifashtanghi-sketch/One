<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_Auth {

    public function __construct() {
        // Intercept wp-login.php
        add_action('login_enqueue_scripts', array($this, 'enqueue_login_styles'));
        add_action('login_form', array($this, 'render_login_form_customization'));
        add_action('login_headerurl', array($this, 'custom_login_header_url'));

        // Replace/Override WooCommerce My-Account Login
        add_action('woocommerce_before_customer_login_form', array($this, 'render_eafd_login_modal'));
        add_shortcode('eafd_login', array($this, 'login_shortcode'));

        // AJAX actions
        add_action('wp_ajax_nopriv_eafd_check_phone', array($this, 'ajax_check_phone'));
        add_action('wp_ajax_eafd_check_phone', array($this, 'ajax_check_phone'));

        add_action('wp_ajax_nopriv_eafd_login_password', array($this, 'ajax_login_password'));
        add_action('wp_ajax_eafd_login_password', array($this, 'ajax_login_password'));

        add_action('wp_ajax_nopriv_eafd_send_otp', array($this, 'ajax_send_otp'));
        add_action('wp_ajax_eafd_send_otp', array($this, 'ajax_send_otp'));

        add_action('wp_ajax_nopriv_eafd_verify_otp', array($this, 'ajax_verify_otp'));
        add_action('wp_ajax_eafd_verify_otp', array($this, 'ajax_verify_otp'));

        add_action('wp_ajax_nopriv_eafd_complete_registration', array($this, 'ajax_complete_registration'));
        add_action('wp_ajax_eafd_complete_registration', array($this, 'ajax_complete_registration'));
    }

    public function custom_login_header_url() {
        return home_url();
    }

    public function enqueue_login_styles() {
        wp_enqueue_style('eafd-sms-frontend', EAFD_SMS_URL . 'assets/css/frontend.css', array(), EAFD_SMS_VERSION);
        wp_enqueue_script('eafd-sms-frontend', EAFD_SMS_URL . 'assets/js/frontend.js', array('jquery'), EAFD_SMS_VERSION, true);

        wp_localize_script('eafd-sms-frontend', 'eafd_sms_obj', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('eafd_sms_nonce'),
            'is_user_logged_in' => is_user_logged_in(),
            'site_title' => get_bloginfo('name')
        ));
    }

    public function render_login_form_customization() {
        // Render custom glassmorphic login UI in wp-login.php
        echo '<style>
            body.login { background: #0f172a !important; font-family: "Vazirmatn", sans-serif !important; direction: rtl !important; }
            #login { width: 100% !important; max-width: 420px !important; padding: 20px !important; }
            #loginform > p, #loginform > div:not(.eafd-login-box) { display: none !important; }
            #loginform { background: transparent !important; border: none !important; box-shadow: none !important; padding: 0 !important; }
            .login h1 a { background-size: contain !important; width: 100% !important; }
        </style>';
        echo $this->get_login_box_html();
    }

    public function render_eafd_login_modal() {
        if (is_user_logged_in()) return;
        echo $this->get_login_box_html();
        echo '<style>.woocommerce-form-login, .woocommerce-form-register { display: none !important; }</style>';
    }

    public function login_shortcode() {
        if (is_user_logged_in()) {
            return '<div class="eafd-alert eafd-alert-info">شما قبلاً وارد سایت شده‌اید. <a href="' . wp_logout_url(home_url()) . '">خروج از حساب</a></div>';
        }
        return $this->get_login_box_html();
    }

    public function get_login_box_html() {
        ob_start();
        ?>
        <div class="eafd-login-box" id="eafd-login-box">
            <div class="eafd-login-header">
                <h3>ورود / ثبت‌نام در <?php echo esc_html(get_bloginfo('name')); ?></h3>
                <p>شماره تلفن همراه، نام کاربری یا ایمیل خود را وارد کنید</p>
            </div>

            <!-- Step 1: Input Phone / Username / Email -->
            <div class="eafd-step eafd-step-phone" id="eafd-step-phone">
                <div class="eafd-input-group">
                    <label for="eafd_phone_input">شماره همراه، نام کاربری یا ایمیل</label>
                    <input type="text" id="eafd_phone_input" placeholder="09123456789 یا admin" dir="ltr" autocomplete="username" autofocus />
                </div>
                <button type="button" class="eafd-btn eafd-btn-primary" id="eafd-btn-check-phone">ادامه</button>
            </div>

            <!-- Step 2: Password Input (If User Has Password) -->
            <div class="eafd-step eafd-step-password" id="eafd-step-password" style="display:none;">
                <div class="eafd-input-group">
                    <label for="eafd_password_input">کلمه عبور حساب کاربری</label>
                    <input type="password" id="eafd_password_input" placeholder="کلمه عبور خود را وارد کنید" />
                </div>
                <button type="button" class="eafd-btn eafd-btn-primary" id="eafd-btn-login-password">ورود با کلمه عبور</button>
                <div class="eafd-alt-action">
                    <button type="button" class="eafd-btn-link" id="eafd-btn-switch-to-otp">فراموشی رمز یا ورود با کد یکبار مصرف (SMS)</button>
                </div>
            </div>

            <!-- Step 3: Verify OTP -->
            <div class="eafd-step eafd-step-otp" id="eafd-step-otp" style="display:none;">
                <p class="eafd-otp-msg">کد ۴ رقمی ارسال شده به شماره <span id="eafd-target-phone"></span> را وارد کنید:</p>
                <div class="eafd-otp-inputs" dir="ltr">
                    <input type="text" maxlength="1" class="eafd-otp-digit" data-idx="1" autofocus />
                    <input type="text" maxlength="1" class="eafd-otp-digit" data-idx="2" />
                    <input type="text" maxlength="1" class="eafd-otp-digit" data-idx="3" />
                    <input type="text" maxlength="1" class="eafd-otp-digit" data-idx="4" />
                </div>
                <button type="button" class="eafd-btn eafd-btn-primary" id="eafd-btn-verify-otp">تایید کد و ورود</button>
                <div class="eafd-resend-box">
                    <span id="eafd-timer">02:00</span>
                    <button type="button" class="eafd-btn-link" id="eafd-btn-resend-otp" style="display:none;">ارسال مجدد کد</button>
                </div>
            </div>

            <!-- Step 4: Name Collection for New Users -->
            <div class="eafd-step eafd-step-name" id="eafd-step-name" style="display:none;">
                <h4>تکمیل اطلاعات حساب جدید</h4>
                <div class="eafd-input-group">
                    <label for="eafd_first_name">نام</label>
                    <input type="text" id="eafd_first_name" placeholder="نام شما" />
                </div>
                <div class="eafd-input-group">
                    <label for="eafd_last_name">نام خانوادگی</label>
                    <input type="text" id="eafd_last_name" placeholder="نام خانوادگی شما" />
                </div>
                <input type="hidden" id="eafd_reg_token" value="" />
                <button type="button" class="eafd-btn eafd-btn-primary" id="eafd-btn-complete-reg">تکمیل ثبت‌نام و ورود</button>
            </div>

            <div class="eafd-login-footer">
                <a href="<?php echo esc_url(home_url()); ?>">بازگشت به صفحه اصلی سایت</a>
            </div>
            <div class="eafd-brand-footer">طراحی شده با EAFD.IR</div>
        </div>
        <?php
        return ob_get_clean();
    }

    /* AJAX Handler: Check Phone and Password existence */
    public function ajax_check_phone() {
        check_ajax_referer('eafd_sms_nonce', 'nonce');

        $identity = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $user = EAFD_Phone_Helper::get_user_by_identity($identity);

        if ($user) {
            // Admin or user with password
            if (!empty($user->user_pass)) {
                wp_send_json_success([
                    'has_password' => true,
                    'identity' => $identity,
                    'message' => 'لطفاً کلمه عبور خود را وارد کنید.'
                ]);
            }
        }

        $normalized = EAFD_Phone_Helper::normalize_phone($identity);
        if (!$normalized) {
            wp_send_json_error(['message' => 'لطفاً شماره تلفن همراه، نام کاربری یا ایمیل معتبر وارد کنید.']);
        }

        // Send OTP directly
        $send_res = $this->send_otp_code($normalized);
        if ($send_res['success']) {
            wp_send_json_success([
                'has_password' => false,
                'phone' => $normalized,
                'is_new' => !$user,
                'message' => 'کد تایید ۴ رقمی برای شما ارسال شد.'
            ]);
        } else {
            wp_send_json_error(['message' => $send_res['message']]);
        }
    }

    public function ajax_login_password() {
        check_ajax_referer('eafd_sms_nonce', 'nonce');

        $identity = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        $user = EAFD_Phone_Helper::get_user_by_identity($identity);

        if (!$user) {
            wp_send_json_error(['message' => 'کاربری با این اطلاعات یافت نشد.']);
        }

        if (wp_check_password($password, $user->user_pass, $user->ID)) {
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true);
            wp_send_json_success(['message' => 'ورود با موفقیت انجام شد.', 'redirect' => home_url()]);
        } else {
            wp_send_json_error(['message' => 'کلمه عبور وارد شده اشتباه است.']);
        }
    }

    public function ajax_send_otp() {
        check_ajax_referer('eafd_sms_nonce', 'nonce');

        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $normalized = EAFD_Phone_Helper::normalize_phone($phone);

        if (!$normalized) {
            wp_send_json_error(['message' => 'شماره همراه معتبر نیست.']);
        }

        $res = $this->send_otp_code($normalized);
        if ($res['success']) {
            wp_send_json_success(['message' => 'کد تایید ۴ رقمی مجدداً ارسال شد.']);
        } else {
            wp_send_json_error(['message' => $res['message']]);
        }
    }

    private function send_otp_code($phone) {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $ip_transient_key = 'eafd_ip_limit_' . md5($ip);
        $ip_count = get_transient($ip_transient_key) ?: 0;

        if ($ip_count >= 10) {
            return [
                'success' => false,
                'message' => 'تعداد درخواست‌های شما بیش از حد مجاز است. لطفاً چند دقیقه دیگر مجدداً تلاش نمایید.'
            ];
        }

        $transient_key = 'eafd_otp_' . $phone;

        // Rate limit: Prevent resending within 60 seconds
        $existing = get_transient($transient_key);
        if ($existing && isset($existing['time']) && (time() - $existing['time']) < 60) {
            return [
                'success' => false,
                'message' => 'لطفاً ۶۰ ثانیه صبر کرده و سپس مجدداً درخواست کد دهید.'
            ];
        }

        set_transient($ip_transient_key, $ip_count + 1, 600);

        $code = sprintf('%04d', rand(1000, 9999));
        set_transient($transient_key, [
            'code' => $code,
            'time' => time()
        ], 300); // 5 mins expiration

        $settings = get_option('eafd_sms_settings', []);
        $template_id = $settings['otp_template_id'] ?? '';
        $var_code = !empty($settings['otp_var_code']) ? $settings['otp_var_code'] : 'CODE';
        $var_site = !empty($settings['otp_var_site']) ? $settings['otp_var_site'] : 'SITE';

        $site_title = get_bloginfo('name');

        $client = new EAFD_SMS_Client();
        return $client->send_pattern($phone, $template_id, [
            $var_code => $code,
            $var_site => $site_title
        ]);
    }

    public function ajax_verify_otp() {
        check_ajax_referer('eafd_sms_nonce', 'nonce');

        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $code = isset($_POST['code']) ? sanitize_text_field($_POST['code']) : '';

        $normalized = EAFD_Phone_Helper::normalize_phone($phone);
        if (!$normalized) {
            wp_send_json_error(['message' => 'شماره همراه معتبر نیست.']);
        }

        $transient_key = 'eafd_otp_' . $normalized;
        $stored = get_transient($transient_key);

        if (!$stored || !isset($stored['code']) || $stored['code'] !== $code) {
            wp_send_json_error(['message' => 'کد وارد شده اشتباه است یا منقضی شده است.']);
        }

        // Delete transient after successful verification
        delete_transient($transient_key);

        // Generate verified token to authorize session completion securely
        $verified_token = wp_generate_password(24, false);
        set_transient('eafd_verified_token_' . $verified_token, $normalized, 600); // 10 mins

        $user = EAFD_Phone_Helper::get_user_by_phone($normalized);

        if (!$user) {
            // New User Registration required
            wp_send_json_success([
                'is_new' => true,
                'token' => $verified_token,
                'message' => 'کد تایید شد. لطفاً نام و نام خانوادگی خود را وارد نمایید.'
            ]);
        } else {
            // Existing User -> Login
            wp_set_current_user($user->ID);
            wp_set_auth_cookie($user->ID, true);
            wp_send_json_success([
                'is_new' => false,
                'token' => $verified_token,
                'message' => 'ورود با موفقیت انجام شد.',
                'redirect' => home_url()
            ]);
        }
    }

    public function ajax_complete_registration() {
        check_ajax_referer('eafd_sms_nonce', 'nonce');

        $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
        $token = isset($_POST['token']) ? sanitize_text_field($_POST['token']) : '';
        $first_name = isset($_POST['first_name']) ? sanitize_text_field($_POST['first_name']) : '';
        $last_name = isset($_POST['last_name']) ? sanitize_text_field($_POST['last_name']) : '';

        $normalized = EAFD_Phone_Helper::normalize_phone($phone);
        if (!$normalized) {
            wp_send_json_error(['message' => 'شماره همراه معتبر نیست.']);
        }

        // SECURITY CHECK: Verify token matches phone number
        $token_phone = get_transient('eafd_verified_token_' . $token);
        if (!$token_phone || $token_phone !== $normalized) {
            wp_send_json_error(['message' => 'احراز هویت غیرمجاز است. لطفاً مجدداً کد دریافت نمایید.']);
        }

        delete_transient('eafd_verified_token_' . $token);

        if (empty($first_name) || empty($last_name)) {
            wp_send_json_error(['message' => 'لطفاً نام و نام خانوادگی خود را کامل وارد کنید.']);
        }

        $user = EAFD_Phone_Helper::get_user_by_phone($normalized);

        if (!$user) {
            $username = $normalized;
            $email = $normalized . '@noemail.eafd.ir';
            $password = wp_generate_password(12, true);

            $user_id = wp_create_user($username, $password, $email);

            if (is_wp_error($user_id)) {
                wp_send_json_error(['message' => 'خطا در ایجاد حساب کاربری: ' . $user_id->get_error_message()]);
            }

            $user = get_user_by('id', $user_id);
        } else {
            $user_id = $user->ID;
        }

        // Update name and phone metas
        wp_update_user([
            'ID' => $user_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'display_name' => trim($first_name . ' ' . $last_name)
        ]);

        EAFD_Phone_Helper::save_user_phone($user_id, $normalized);

        wp_set_current_user($user_id);
        wp_set_auth_cookie($user_id, true);

        wp_send_json_success([
            'message' => 'حساب کاربری با موفقیت ایجاد شد.',
            'redirect' => home_url()
        ]);
    }
}

new EAFD_Auth();
