<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Helper;

class SmsClient {
    private string $apiUrl = 'https://api.sms.ir/v1/send/verify';

    private function getSetting(string $key, string $default = ''): string {
        try {
            $row = Database::fetch("SELECT setting_value FROM settings WHERE setting_key = ?", [$key]);
            return $row['setting_value'] ?? $default;
        } catch (\Exception $e) {
            return $default;
        }
    }

    /**
     * Send OTP Pattern SMS via SMS.ir Fast Send API
     */
    public function sendOtp(string $phone, string $code): array {
        $normPhone = Helper::normalizePhone($phone);
        if (!$normPhone) {
            return [
                'success' => false,
                'message' => 'شماره تلفن همراه وارد شده معتبر نیست.'
            ];
        }

        $apiKey = $this->getSetting('sms_api_key');
        $templateId = $this->getSetting('sms_template_id');
        $paramName = $this->getSetting('sms_param_name', 'CODE');

        if (empty($apiKey) || empty($templateId)) {
            return [
                'success' => false,
                'message' => 'تنظیمات کلید API یا شناسه پترن SMS.ir در دیتابیس ثبت نشده است.'
            ];
        }

        $payload = [
            'mobile' => $normPhone,
            'templateId' => (int)$templateId,
            'parameters' => [
                [
                    'name' => $paramName,
                    'value' => (string)$code
                ]
            ]
        ];

        $ch = curl_init($this->apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'X-API-KEY: ' . $apiKey,
            'Content-Type: application/json',
            'Accept: application/json'
        ]);

        if (defined('CURLOPT_IPRESOLVE') && defined('CURL_IPRESOLVE_V4')) {
            curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlErr = curl_error($ch);
        curl_close($ch);

        if ($curlErr) {
            return [
                'success' => false,
                'message' => 'خطا در ارتباط با سامانه پیامک: ' . $curlErr
            ];
        }

        $resData = json_decode($response, true);
        if ($httpCode === 200 && isset($resData['status']) && $resData['status'] == 1) {
            return [
                'success' => true,
                'message' => 'کد تایید پیامکی با موفقیت ارسال شد.'
            ];
        }

        $msg = $resData['message'] ?? 'خطا در ارسال پیامک (کد وضعیت: ' . $httpCode . ')';
        return [
            'success' => false,
            'message' => $msg
        ];
    }
}
