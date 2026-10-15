<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_Phone_Helper {

    /**
     * Convert Persian and Arabic numbers to English digits.
     */
    public static function convert_persian_to_english($string) {
        $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
        $arabic  = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];
        $num     = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];

        $converted = str_replace($persian, $num, $string);
        return str_replace($arabic, $num, $converted);
    }

    /**
     * Normalize phone number to 09XXXXXXXXX standard Iranian format.
     */
    public static function normalize_phone($phone) {
        if (empty($phone)) {
            return false;
        }

        $phone = self::convert_persian_to_english(trim($phone));
        $phone = preg_replace('/[^\d]/', '', $phone);

        if (preg_match('/^(?:0098|98|\+98)?(9\d{9})$/', $phone, $matches)) {
            return '0' . $matches[1];
        }

        if (preg_match('/^0?(9\d{9})$/', $phone, $matches)) {
            return '0' . $matches[1];
        }

        return false;
    }

    /**
     * Validate if string is valid Iranian mobile number
     */
    public static function is_valid_mobile($phone) {
        $normalized = self::normalize_phone($phone);
        return $normalized && preg_match('/^09\d{9}$/', $normalized);
    }

    /**
     * Find user by phone number, username, or email address.
     */
    public static function get_user_by_identity($identity) {
        $identity = trim($identity);
        if (empty($identity)) {
            return false;
        }

        // 1. Check if email
        if (is_email($identity)) {
            $user = get_user_by('email', $identity);
            if ($user) return $user;
        }

        // 2. Check if username
        $user = get_user_by('login', $identity);
        if ($user) return $user;

        // 3. Check if phone
        $normalized = self::normalize_phone($identity);
        if ($normalized) {
            return self::get_user_by_phone($normalized);
        }

        return false;
    }

    /**
     * Find user by phone number across all meta keys and username formats.
     */
    public static function get_user_by_phone($phone) {
        $normalized = self::normalize_phone($phone);
        if (!$normalized) {
            return false;
        }

        // 1. Search by username
        $user = get_user_by('login', $normalized);
        if ($user) {
            return $user;
        }

        // 2. Search by billing_phone meta
        $users = get_users([
            'meta_key' => 'billing_phone',
            'meta_value' => $normalized,
            'number' => 1
        ]);

        if (!empty($users)) {
            return $users[0];
        }

        // 3. Search by digits_phone or phone_number meta
        $users_digits = get_users([
            'meta_query' => [
                'relation' => 'OR',
                ['key' => 'digits_phone', 'value' => $normalized],
                ['key' => 'mobile', 'value' => $normalized],
                ['key' => 'phone_number', 'value' => $normalized]
            ],
            'number' => 1
        ]);

        if (!empty($users_digits)) {
            return $users_digits[0];
        }

        return false;
    }

    /**
     * Save/Update normalized phone to user meta across WooCommerce and plugin keys
     */
    public static function save_user_phone($user_id, $phone) {
        $normalized = self::normalize_phone($phone);
        if (!$normalized || !$user_id) {
            return false;
        }

        update_user_meta($user_id, 'billing_phone', $normalized);
        update_user_meta($user_id, 'digits_phone', $normalized);
        update_user_meta($user_id, 'phone_number', $normalized);
        update_user_meta($user_id, 'mobile', $normalized);

        return true;
    }
}
