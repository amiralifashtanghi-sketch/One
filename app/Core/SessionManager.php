<?php
namespace App\Core;

class SessionManager {
    public static function start(): void {
        if (session_status() === PHP_SESSION_NONE) {
            $config = require __DIR__ . '/../../config/app.php';
            $secConfig = $config['security'];

            ini_set('session.name', $secConfig['session_name']);
            ini_set('session.use_strict_mode', '1');
            ini_set('session.use_only_cookies', '1');
            ini_set('session.cookie_httponly', '1');
            ini_set('session.cookie_samesite', 'Lax');
            ini_set('session.gc_maxlifetime', (string)$secConfig['session_lifetime']);

            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                ini_set('session.cookie_secure', '1');
            }

            session_start();

            if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > $secConfig['session_lifetime'])) {
                session_unset();
                session_destroy();
                session_start();
            }
            $_SESSION['LAST_ACTIVITY'] = time();
        }
    }

    public static function regenerate(): void {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            self::start();
        }
        session_regenerate_id(true);
    }

    public static function set(string $key, $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    public static function get(string $key, $default = null) {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    public static function remove(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    public static function destroy(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_unset();
            session_destroy();
        }
    }
}
