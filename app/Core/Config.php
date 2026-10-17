<?php

namespace App\Core;

class Config {
    private static array $items = [];

    public static function load(string $file): void {
        if (file_exists($file)) {
            $data = require $file;
            if (is_array($data)) {
                self::$items = array_merge(self::$items, $data);
            }
        }
    }

    public static function set(string $key, mixed $value): void {
        self::$items[$key] = $value;
    }

    public static function get(string $key, mixed $default = null): mixed {
        return self::$items[$key] ?? $default;
    }

    public static function all(): array {
        return self::$items;
    }
}
