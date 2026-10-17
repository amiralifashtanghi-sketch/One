<?php

namespace App\Core;

class Request {
    public static function uri(): string {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');

        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }

        $uri = rawurldecode($uri);
        return '/' . trim($uri, '/');
    }

    public static function method(): string {
        return strtoupper($_SERVER['REQUEST_METHOD'] ?? 'GET');
    }

    public static function get(string $key = null, mixed $default = null): mixed {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    public static function post(string $key = null, mixed $default = null): mixed {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    public static function json(): array {
        $content = file_get_contents('php://input');
        $data = json_decode($content, true);
        return is_array($data) ? $data : [];
    }

    public static function input(string $key, mixed $default = null): mixed {
        $post = self::post();
        if (isset($post[$key])) {
            return $post[$key];
        }
        $get = self::get();
        if (isset($get[$key])) {
            return $get[$key];
        }
        $json = self::json();
        if (isset($json[$key])) {
            return $json[$key];
        }
        return $default;
    }

    public static function file(string $key): ?array {
        return $_FILES[$key] ?? null;
    }

    public static function isAjax(): bool {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
