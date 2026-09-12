<?php

namespace App\Services;

use App\Core\Database;

class RateLimiter
{
    public static function check(string $key, int $maxAttempts = 10, int $decaySeconds = 60): bool
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $fullKey = hash('sha256', $key . '_' . $ip);
        $now = time();

        // Clean expired limits
        Database::query("DELETE FROM rate_limits WHERE reset_at < ?", [$now]);

        $record = Database::fetch("SELECT * FROM rate_limits WHERE id = ?", [$fullKey]);

        if (!$record) {
            Database::query("INSERT INTO rate_limits (id, attempts, reset_at) VALUES (?, 1, ?)", [$fullKey, $now + $decaySeconds]);
            return true;
        }

        if ($record['attempts'] >= $maxAttempts) {
            return false;
        }

        Database::query("UPDATE rate_limits SET attempts = attempts + 1 WHERE id = ?", [$fullKey]);
        return true;
    }
}
