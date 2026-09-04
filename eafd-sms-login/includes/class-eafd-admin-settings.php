<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_SMS_Admin_Settings {

    private $option_key = 'eafd_sms_settings';

    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    public function add_admin_menu() {
        add_menu_page(
            'ورود پیامکی EAFD',
            'ورود پیامکی EAFD',
            'manage_options',
            'eafd-sms-settings',
            array($this, 'render_admin_page'),
            'dashicons-smartphone',
            58
        );

        add_submenu_page(
            'eafd-sms-settings',
            'تنظیمات پیامک',
            'تنظیمات پیامک',
            'manage_options',
            'eafd-sms-settings',
            array($this, 'render_admin_page')
        );

        add_submenu_page(
            'eafd-sms-settings',
            'بانک شماره‌ها و خروجی',
            'بانک شماره‌ها و خروجی',
            'manage_options',
            'eafd-sms-export',
            array('EAFD_SMS_Export', 'render_export_page')
        );

        add_submenu_page(
            'eafd-sms-settings',
            'گزارش و لاگ پیامک‌ها',
            'گزارش پیامک‌ها',
            'manage_options',
            'eafd-sms-logs',
            array($this, 'render_logs_page')
        );
    }

    public function register_settings() {
        register_setting('eafd_sms_options_group', $this->option_key, array($this, 'sanitize_settings'));
    }

    public function sanitize_settings($input) {
        $sanitized = [];
        $sanitized['api_key'] = sanitize_text_field($input['api_key'] ?? '');
        $sanitized['line_number'] = sanitize_text_field($input['line_number'] ?? '');
        $sanitized['admin_phone'] = EAFD_Phone_Helper::normalize_phone($input['admin_phone'] ?? '') ?: sanitize_text_field($input['admin_phone'] ?? '');

        $sanitized['otp_template_id'] = sanitize_text_field($input['otp_template_id'] ?? '');
        $sanitized['otp_var_code'] = sanitize_text_field($input['otp_var_code'] ?? 'CODE');
        $sanitized['otp_var_site'] = sanitize_text_field($input['otp_var_site'] ?? 'SITE');

        $sanitized['order_customer_template_id'] = sanitize_text_field($input['order_customer_template_id'] ?? '');
        $sanitized['order_customer_var_site'] = sanitize_text_field($input['order_customer_var_site'] ?? 'SITE');
        $sanitized['order_customer_var_order'] = sanitize_text_field($input['order_customer_var_order'] ?? 'ORDER_ID');
        $sanitized['order_customer_var_status'] = sanitize_text_field($input['order_customer_var_status'] ?? 'STATUS');

        $sanitized['order_admin_template_id'] = sanitize_text_field($input['order_admin_template_id'] ?? '');
        $sanitized['order_admin_var_site'] = sanitize_text_field($input['order_admin_var_site'] ?? 'SITE');
        $sanitized['order_admin_var_order'] = sanitize_text_field($input['order_admin_var_order'] ?? 'ORDER_ID');

        $sanitized['enable_wc_checkout_verify'] = isset($input['enable_wc_checkout_verify']) ? 1 : 0;
        $sanitized['disable_email_req'] = isset($input['disable_email_req']) ? 1 : 0;

        return $sanitized;
    }

    public function render_admin_page() {
        $settings = get_option($this->option_key, [
            'api_key' => '',
            'line_number' => '',
            'admin_phone' => '',
            'otp_template_id' => '',
            'otp_var_code' => 'CODE',
            'otp_var_site' => 'SITE',
            'order_customer_template_id' => '',
            'order_customer_var_site' => 'SITE',
            'order_customer_var_order' => 'ORDER_ID',
            'order_customer_var_status' => 'STATUS',
            'order_admin_template_id' => '',
            'order_admin_var_site' => 'SITE',
            'order_admin_var_order' => 'ORDER_ID',
            'enable_wc_checkout_verify' => 1,
            'disable_email_req' => 1,
        ]);
        ?>
        <div class="wrap eafd-admin-wrap">
            <h1 class="eafd-admin-title">تنظیمات ورود پیامکی و اطلاع‌رسانی SMS.ir (EAFD)</h1>
            <div class="eafd-badge-tag">ساخته شده توسط EAFD.ir</div>

            <form method="post" action="options.php" class="eafd-admin-form">
                <?php settings_fields('eafd_sms_options_group'); ?>

                <div class="eafd-card">
                    <h2>🔑 ۱. کلید اتصال SMS.ir و شماره خط</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">کلید API وب‌سرویس SMS.ir</th>
                            <td>
                                <input type="password" name="<?php echo $this->option_key; ?>[api_key]" value="<?php echo esc_attr($settings['api_key'] ?? ''); ?>" class="regular-text" placeholder="کلید API دریافت شده از sms.ir" />
                                <p class="description">کلید API خود را از پنل SMS.ir بخش کلیدهای API دریافت و اینجا وارد کنید.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">شماره خط فرستنده (اختیاری)</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[line_number]" value="<?php echo esc_attr($settings['line_number'] ?? ''); ?>" class="regular-text" placeholder="مثلا 3000xxxx" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">شماره موبایل مدیر جهت دریافت پیامک سفارشات</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[admin_phone]" value="<?php echo esc_attr($settings['admin_phone'] ?? ''); ?>" class="regular-text" placeholder="09150591710" />
                                <p class="description">شماره موبایل مدیر سایت که پیامک «سفارش جدید» به آن ارسال می‌شود.</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="eafd-card">
                    <h2>📲 ۲. تنظیمات پترن ورود پیامکی (OTP)</h2>
                    <p class="description">عنوان سایت جهت ارسال در پیامک به صورت خودکار از «عنوان سایت» در وردپرس (<?php echo esc_html(get_bloginfo('name')); ?>) برداشته می‌شود.</p>
                    <table class="form-table">
                        <tr>
                            <th scope="row">شناسه قالب/پترن ورود (Template ID)</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[otp_template_id]" value="<?php echo esc_attr($settings['otp_template_id'] ?? ''); ?>" class="regular-text" placeholder="مثلا 100001" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">نام متغیر «کد ورود» در پترن SMS.ir</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[otp_var_code]" value="<?php echo esc_attr($settings['otp_var_code'] ?? 'CODE'); ?>" class="regular-text" />
                                <p class="description">مثال: اگر در متن پترن sms.ir متغیر به شکل <code>#CODE#</code> تعریف شده، نام متغیر را <code>CODE</code> وارد کنید.</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">نام متغیر «نام سایت» در پترن SMS.ir</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[otp_var_site]" value="<?php echo esc_attr($settings['otp_var_site'] ?? 'SITE'); ?>" class="regular-text" />
                                <p class="description">مثال: اگر متغیر نام سایت به شکل <code>#SITE#</code> است، نام متغیر را <code>SITE</code> وارد کنید.</p>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="eafd-card">
                    <h2>🛍️ ۳. تنظیمات پترن پیامک خریدار/مشتری ووکامرس</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">شناسه پترن اطلاع‌رسانی سفارش به مشتری</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[order_customer_template_id]" value="<?php echo esc_attr($settings['order_customer_template_id'] ?? ''); ?>" class="regular-text" placeholder="مثلا 100002" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">نام متغیر «نام سایت»</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[order_customer_var_site]" value="<?php echo esc_attr($settings['order_customer_var_site'] ?? 'SITE'); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">نام متغیر «شماره سفارش»</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[order_customer_var_order]" value="<?php echo esc_attr($settings['order_customer_var_order'] ?? 'ORDER_ID'); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">نام متغیر «وضعیت سفارش»</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[order_customer_var_status]" value="<?php echo esc_attr($settings['order_customer_var_status'] ?? 'STATUS'); ?>" class="regular-text" />
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="eafd-card">
                    <h2>🔔 ۴. تنظیمات پترن پیامک مدیر سایت (سفارش جدید)</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">شناسه پترن اعلان سفارش جدید به مدیر</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[order_admin_template_id]" value="<?php echo esc_attr($settings['order_admin_template_id'] ?? ''); ?>" class="regular-text" placeholder="مثلا 100003" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">نام متغیر «نام سایت»</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[order_admin_var_site]" value="<?php echo esc_attr($settings['order_admin_var_site'] ?? 'SITE'); ?>" class="regular-text" />
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">نام متغیر «شماره سفارش»</th>
                            <td>
                                <input type="text" name="<?php echo $this->option_key; ?>[order_admin_var_order]" value="<?php echo esc_attr($settings['order_admin_var_order'] ?? 'ORDER_ID'); ?>" class="regular-text" />
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="eafd-card">
                    <h2>⚙️ ۵. تنظیمات عمومی رفتار ووکامرس و تسویه حساب</h2>
                    <table class="form-table">
                        <tr>
                            <th scope="row">پاپ‌آپ واریفای شماره تلفن در برگه تسویه حساب (Checkout)</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="<?php echo $this->option_key; ?>[enable_wc_checkout_verify]" value="1" <?php checked(1, $settings['enable_wc_checkout_verify'] ?? 1); ?> />
                                    فعال‌سازی پاپ‌آپ استعلام و تایید پیامکی شماره تلفن برای کاربران غیر وارد شده پیش از ثبت نهایی سفارش
                                </label>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">اختیاری کردن ایمیل</th>
                            <td>
                                <label>
                                    <input type="checkbox" name="<?php echo $this->option_key; ?>[disable_email_req]" value="1" <?php checked(1, $settings['disable_email_req'] ?? 1); ?> />
                                    حذف شرط اجباری بودن فیلد ایمیل در تسویه حساب ووکامرس و فرم‌ها
                                </label>
                            </td>
                        </tr>
                    </table>
                </div>

                <?php submit_button('ذخیره تغییرات تنظیمات', 'primary', 'submit_eafd_sms'); ?>
            </form>
        </div>
        <?php
    }

    public function render_logs_page() {
        $logs = EAFD_SMS_Logger::get_logs();
        ?>
        <div class="wrap eafd-admin-wrap">
            <h1 class="eafd-admin-title">گزارش و لاگ ارسال پیامک‌ها</h1>
            <div class="eafd-badge-tag">EAFD.ir Logs</div>

            <div class="eafd-card" style="margin-top: 20px;">
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 160px;">زمان</th>
                            <th style="width: 90px;">نوع</th>
                            <th>پیام</th>
                            <th>داده‌ها</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($logs)) : ?>
                            <tr><td colspan="4">هیچ لاگی ثبت نشده است.</td></tr>
                        <?php else : ?>
                            <?php foreach ($logs as $log) : ?>
                                <tr>
                                    <td><?php echo esc_html($log['time']); ?></td>
                                    <td>
                                        <span class="eafd-log-badge eafd-log-<?php echo esc_attr($log['type']); ?>">
                                            <?php echo esc_html(strtoupper($log['type'])); ?>
                                        </span>
                                    </td>
                                    <td><?php echo esc_html($log['message']); ?></td>
                                    <td><code><?php echo esc_html(json_encode($log['data'], JSON_UNESCAPED_UNICODE)); ?></code></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
    }
}

new EAFD_SMS_Admin_Settings();
