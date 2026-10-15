<?php

namespace App\Core;

class Logger
{
    public static function log(string $level, string $message, array $context = []): void
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $timestamp = date('Y-m-d H:i:s');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $userId = Auth::id() ?? 0;
        $contextJson = !empty($context) ? json_encode($context, JSON_UNESCAPED_UNICODE) : '';

        $logLine = "[{$timestamp}] [{$level}] [IP: {$ip}] [User: {$userId}] {$message} {$contextJson}\n";

        file_put_contents($logDir . '/activity.log', $logLine, FILE_APPEND | LOCK_EX);

        try {
            Database::query(
                "INSERT INTO activity_logs (user_id, action, context, ip_address, created_at) VALUES (?, ?, ?, ?, ?)",
                [$userId, $message, $contextJson, $ip, $timestamp]
            );
        } catch (\Throwable $e) {
            // Ignore DB log failure if table not created
        }
    }

    public static function info(string $message, array $context = []): void
    {
        self::log('INFO', $message, $context);
    }

    public static function warning(string $message, array $context = []): void
    {
        self::log('WARNING', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::log('ERROR', $message, $context);
    }
}
