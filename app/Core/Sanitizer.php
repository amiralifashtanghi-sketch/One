<?php

namespace App\Core;

class Sanitizer
{
    public static function clean(mixed $data): mixed
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = self::clean($value);
            }
            return $data;
        }

        if (is_string($data)) {
            return htmlspecialchars(trim($data), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
        }

        return $data;
    }

    public static function normalizeDigits(string $str): string
    {
        $farsi = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $english = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $str = str_replace($farsi, $english, $str);
        return str_replace($arabic, $english, $str);
    }

    public static function cleanPhone(string $phone): string
    {
        $phone = self::normalizeDigits($phone);
        $phone = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($phone, '989')) {
            $phone = '0' . substr($phone, 2);
        } elseif (str_starts_with($phone, '9') && strlen($phone) === 10) {
            $phone = '0' . $phone;
        }

        return $phone;
    }

    public static function sanitizeSlug(string $title): string
    {
        $title = trim($title);
        $title = mb_strtolower($title, 'UTF-8');
        $title = preg_replace('/[^\p{L}\p{N}\s-]/u', '', $title);
        $title = preg_replace('/[\s-]+/u', '-', $title);
        return trim($title, '-');
    }
}
