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

    /**
     * Gregorian to Jalali Date Conversion
     */
    public static function toJalali(string $datetime): string {
        if (empty($datetime) || $datetime === '0000-00-00 00:00:00') {
            return '-';
        }
        $timestamp = strtotime($datetime);
        if (!$timestamp) return $datetime;

        $g_y = date('Y', $timestamp);
        $g_m = date('m', $timestamp);
        $g_d = date('d', $timestamp);
        $time = date('H:i', $timestamp);

        $g_days_in_month = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];
        $j_days_in_month = [31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29];

        $gy = $g_y - 1600;
        $gm = $g_m - 1;
        $gd = $g_d - 1;

        $g_day_no = 365 * $gy + floor(($gy + 3) / 4) - floor(($gy + 99) / 100) + floor(($gy + 399) / 400);

        for ($i = 0; $i < $gm; ++$i) {
            $g_day_no += $g_days_in_month[$i];
        }
        if ($gm > 1 && (($gy % 4 == 0 && $gy % 100 != 0) || ($gy % 400 == 0))) {
            $g_day_no++;
        }
        $g_day_no += $gd;

        $j_day_no = $g_day_no - 79;

        $j_np = floor($j_day_no / 12053);
        $j_day_no %= 12053;

        $jy = 979 + 33 * $j_np + 4 * floor($j_day_no / 1461);

        $j_day_no %= 1461;

        if ($j_day_no >= 366) {
            $jy += floor(($j_day_no - 1) / 365);
            $j_day_no = ($j_day_no - 1) % 365;
        }

        for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) {
            $j_day_no -= $j_days_in_month[$i];
        }
        $jm = $i + 1;
        $jd = $j_day_no + 1;

        return sprintf('%04d/%02d/%02d %s', $jy, $jm, $jd, $time);
    }
}
