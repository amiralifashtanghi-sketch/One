<?php

namespace App\Services;

use App\Core\Database;
use App\Core\Helper;

class OtpService {
    private SmsClient $smsClient;

    public function __construct() {
        $this->smsClient = new SmsClient();
    }

    /**
     * Generate and send 4-digit OTP code
     */
    public function sendOtp(string $phone): array {
        $normPhone = Helper::normalizePhone($phone);
        if (!$normPhone) {
            return [
                'success' => false,
                'message' => 'شماره همراه وارد شده معتبر نیست.'
            ];
        }

        // Generate 4 digit code
        $code = (string)rand(1000, 9999);
        $expiresAt = date('Y-m-d H:i:s', strtotime('+2 minutes'));

        // Save to DB
        Database::query(
            "INSERT INTO otps (phone, code, expires_at) VALUES (?, ?, ?)",
            [$normPhone, $code, $expiresAt]
        );

        // Send SMS
        return $this->smsClient->sendOtp($normPhone, $code);
    }

    /**
     * Verify OTP code
     */
    public function verifyOtp(string $phone, string $code): bool {
        $normPhone = Helper::normalizePhone($phone);
        $code = Helper::convertDigitsToEnglish(trim($code));

        if (!$normPhone || empty($code)) {
            return false;
        }

        $now = date('Y-m-d H:i:s');
        $row = Database::fetch(
            "SELECT id FROM otps WHERE phone = ? AND code = ? AND expires_at >= ? AND used = 0 ORDER BY id DESC LIMIT 1",
            [$normPhone, $code, $now]
        );

        if ($row) {
            // Mark used
            Database::query("UPDATE otps SET used = 1 WHERE id = ?", [$row['id']]);
            return true;
        }

        return false;
    }

    /**
     * Reset user password after verifying OTP
     */
    public function resetPasswordWithOtp(string $phone, string $code, string $newPassword): array {
        $normPhone = Helper::normalizePhone($phone);
        if (!$normPhone) {
            return [
                'success' => false,
                'message' => 'شماره همراه معتبر نیست.'
            ];
        }

        if (strlen($newPassword) < 6) {
            return [
                'success' => false,
                'message' => 'رمز عبور جدید باید حداقل ۶ کاراکتر باشد.'
            ];
        }

        if (!$this->verifyOtp($normPhone, $code)) {
            return [
                'success' => false,
                'message' => 'کد تایید پیامکی نامعتبر است یا منقضی شده است.'
            ];
        }

        $user = Database::fetch("SELECT id FROM users WHERE phone = ?", [$normPhone]);
        if (!$user) {
            return [
                'success' => false,
                'message' => 'حساب کاربری با این شماره همراه پیدا نشد.'
            ];
        }

        $hash = Helper::hashPassword($newPassword);
        Database::query("UPDATE users SET password = ? WHERE id = ?", [$hash, $user['id']]);

        return [
            'success' => true,
            'message' => 'رمز عبور شما با موفقیت تغییر یافت. اکنون می‌توانید وارد شوید.'
        ];
    }
}
