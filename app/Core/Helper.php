<?php

namespace App\Core;

class Helper {
    /**
     * Convert Persian and Arabic digits to English digits
     */
    public static function convertDigitsToEnglish(string $string): string {
        $persianDigits = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabicDigits  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $englishDigits = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $string = str_replace($persianDigits, $englishDigits, $string);
        return str_replace($arabicDigits, $englishDigits, $string);
    }

    /**
     * Normalize Iranian mobile phone number to 11 digits format (e.g. 09123456789)
     */
    public static function normalizePhone(string $phone): ?string {
        $phone = self::convertDigitsToEnglish(trim($phone));
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (strlen($phone) === 10 && strpos($phone, '9') === 0) {
            $phone = '0' . $phone;
        } elseif (strlen($phone) === 12 && strpos($phone, '989') === 0) {
            $phone = '0' . substr($phone, 2);
        }

        if (preg_match('/^09[0-9]{9}$/', $phone)) {
            return $phone;
        }

        return null;
    }

    /**
     * Generate URL slug
     */
    public static function slugify(string $text): string {
        $text = trim($text);
        $text = mb_strtolower($text, 'UTF-8');
        $text = preg_replace('/[^\p{L}\p{N}]+/u', '-', $text);
        return trim($text, '-');
    }

    /**
     * Generate random string/code
     */
    public static function randomCode(int $length = 8): string {
        $bytes = random_bytes((int)ceil($length / 2));
        return substr(bin2hex($bytes), 0, $length);
    }

    /**
     * Hash password securely
     */
    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => 12]);
    }

    /**
     * Verify hashed password
     */
    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    /**
     * Sanitize string input
     */
    public static function sanitize(string $input): string {
        return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
    }
}
