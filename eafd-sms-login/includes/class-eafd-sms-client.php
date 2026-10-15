<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_SMS_Client {

    private $api_key;
    private $line_number;
    private $api_url = 'https://api.sms.ir/v1/send/verify';

    public function __construct() {
        $settings = get_option('eafd_sms_settings', []);
        $this->api_key = isset($settings['api_key']) ? trim($settings['api_key']) : '';
        $this->line_number = isset($settings['line_number']) ? trim($settings['line_number']) : '';
    }

    /**
     * Send pattern SMS via SMS.ir Fast Send API (v1/send/verify)
     *
     * @param string $phone Mobile number
     * @param string|int $template_id Pattern Code
     * @param array $parameters Array of key-value parameters e.g. [['name' => 'CODE', 'value' => '1234']]
     * @return array
     */
    public function send_pattern($phone, $template_id, $parameters = []) {
        $normalized_phone = EAFD_Phone_Helper::normalize_phone($phone);

        if (!$normalized_phone) {
            return [
                'success' => false,
                'message' => 'شماره تلفن همراه وارد شده معتبر نیست.'
            ];
        }

        if (empty($this->api_key)) {
            EAFD_SMS_Logger::log('ارسال پیامک ناموفق: کلید API در تنظیمات افزونه ثبت نشده است.', 'error');
            return [
                'success' => false,
                'message' => 'کلید API پیامک SMS.ir در تنظیمات افزونه وارد نشده است.'
            ];
        }

        if (empty($template_id)) {
            EAFD_SMS_Logger::log('ارسال پیامک ناموفق: شناسه پترن تنظیم نشده است.', 'error');
            return [
                'success' => false,
                'message' => 'شناسه پترن پیامک در تنظیمات افزونه ثبت نشده است.'
            ];
        }

        // Prepare SMS.ir parameter payload format
        $api_parameters = [];
        foreach ($parameters as $name => $value) {
            $api_parameters[] = [
                'name' => (string)$name,
                'value' => (string)$value
            ];
        }

        $body = [
            'mobile' => $normalized_phone,
            'templateId' => (int)$template_id,
            'parameters' => $api_parameters
        ];

        // Force IPv4 resolution to prevent cURL 10-second IPv6 DNS resolution timeouts
        $force_ipv4 = function($handle) {
            if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
                curl_setopt($handle, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
            }
        };

        add_action('http_api_curl', $force_ipv4, 10, 1);

        $response = wp_remote_post($this->api_url, [
            'headers' => [
                'X-API-KEY' => $this->api_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'body' => json_encode($body),
            'timeout' => 20,
            'sslverify' => false,
            'httpversion' => '1.1'
        ]);

        remove_action('http_api_curl', $force_ipv4, 10);

        if (is_wp_error($response)) {
            $error_msg = $response->get_error_message();
            EAFD_SMS_Logger::log('خطا در ارتباط با وب‌سرویس SMS.ir: ' . $error_msg, 'error', ['phone' => $normalized_phone]);

            $user_msg = 'خطا ۱۰۰۰ لطفاً دوباره تلاش کنید';
            if (strpos($error_msg, 'timed out') !== false || strpos($error_msg, 'cURL error 28') !== false) {
                $user_msg = 'خطا ۱۰۰۰ لطفاً دوباره تلاش کنید';
            }

            return [
                'success' => false,
                'message' => $user_msg
            ];
        }

        $status_code = wp_remote_retrieve_response_code($response);
        $res_body = json_decode(wp_remote_retrieve_body($response), true);

        if ($status_code === 200 && isset($res_body['status']) && $res_body['status'] == 1) {
            EAFD_SMS_Logger::log('ارسال موفق پیامک پترن (' . $template_id . ') به ' . $normalized_phone, 'success', $res_body);
            return [
                'success' => true,
                'message' => 'پیامک با موفقیت ارسال شد.',
                'data' => $res_body
            ];
        }

        $msg = isset($res_body['message']) ? $res_body['message'] : 'خطای نامشخص از سامانه پیامک (کد: ' . $status_code . ')';
        EAFD_SMS_Logger::log('ارسال پیامک ناموفق: ' . $msg, 'error', ['body' => $res_body, 'code' => $status_code]);

        return [
            'success' => false,
            'message' => $msg
        ];
    }
}
