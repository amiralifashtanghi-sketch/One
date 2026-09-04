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
            EAFD_SMS_Logger::log('ارسال پیامک ناموفق: کلید API تنظیم نشده است.', 'error');
            return [
                'success' => false,
                'message' => 'کلید API پیامک تنظیم نشده است.'
            ];
        }

        if (empty($template_id)) {
            EAFD_SMS_Logger::log('ارسال پیامک ناموفق: شناسه پترن مشخص نشده است.', 'error');
            return [
                'success' => false,
                'message' => 'شناسه پترن تنظیم نشده است.'
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

        $response = wp_remote_post($this->api_url, [
            'headers' => [
                'X-API-KEY' => $this->api_key,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json'
            ],
            'body' => json_encode($body),
            'timeout' => 15
        ]);

        if (is_wp_error($response)) {
            $error_msg = $response->get_error_message();
            EAFD_SMS_Logger::log('خطا در ارتباط با وب‌سرویس SMS.ir: ' . $error_msg, 'error', ['phone' => $normalized_phone]);
            return [
                'success' => false,
                'message' => 'خطا در ارتباط با سامانه پیامک: ' . $error_msg
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
