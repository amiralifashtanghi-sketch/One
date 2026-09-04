<?php
if (!defined('ABSPATH')) {
    exit;
}

class EAFD_SMS_Logger {

    private static $option_key = 'eafd_sms_logs';

    public static function log($message, $type = 'info', $data = []) {
        $logs = get_option(self::$option_key, []);
        if (!is_array($logs)) {
            $logs = [];
        }

        // Limit logs to last 200 entries
        if (count($logs) > 200) {
            array_shift($logs);
        }

        $logs[] = [
            'time' => current_time('mysql'),
            'type' => $type,
            'message' => $message,
            'data' => $data
        ];

        update_option(self::$option_key, $logs, false);
    }

    public static function get_logs() {
        $logs = get_option(self::$option_key, []);
        return is_array($logs) ? array_reverse($logs) : [];
    }

    public static function clear_logs() {
        delete_option(self::$option_key);
    }
}
