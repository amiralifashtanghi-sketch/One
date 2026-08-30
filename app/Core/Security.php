<?php
namespace App\Core;

class Security {
    public static function setSecurityHeaders(): void {
        header('X-Frame-Options: SAMEORIGIN');
        header('X-Content-Type-Options: nosniff');
        header('X-XSS-Protection: 1; mode=block');
        header('Referrer-Policy: strict-origin-when-cross-origin');
        header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; font-src 'self'; img-src 'self' data:; connect-src 'self';");
        header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
    }

    public static function escape(?string $str): string {
        if ($str === null) return '';
        return htmlspecialchars($str, ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
    }

    public static function sanitizeString(?string $str): string {
        if ($str === null) return '';
        return trim(strip_tags($str));
    }

    public static function sanitizeEmail(?string $email): string {
        if ($email === null) return '';
        return filter_var(trim($email), FILTER_SANITIZE_EMAIL);
    }

    public static function hashPassword(string $password): string {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 2
        ]);
    }

    public static function verifyPassword(string $password, string $hash): bool {
        return password_verify($password, $hash);
    }

    public static function generateCSRFToken(): string {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verifyCSRFToken(?string $token): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }
        return hash_equals($_SESSION['csrf_token'], $token);
    }

    public static function checkRateLimit(string $key, int $maxRequests = 5, int $decaySeconds = 60): bool {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $time = time();
        if (!isset($_SESSION['rate_limits'][$key])) {
            $_SESSION['rate_limits'][$key] = [];
        }

        $_SESSION['rate_limits'][$key] = array_filter(
            $_SESSION['rate_limits'][$key],
            fn($timestamp) => $timestamp > ($time - $decaySeconds)
        );

        if (count($_SESSION['rate_limits'][$key]) >= $maxRequests) {
            return false;
        }

        $_SESSION['rate_limits'][$key][] = $time;
        return true;
    }
}
