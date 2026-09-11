<?php

namespace App\Core;

class Config
{
    protected static array $items = [];

    public static function load(string $configDir): void
    {
        if (!is_dir($configDir)) {
            return;
        }

        $files = glob($configDir . '/*.php');
        foreach ($files as $file) {
            $key = pathinfo($file, PATHINFO_FILENAME);
            $content = require $file;
            if (is_array($content)) {
                self::$items[$key] = $content;
            }
        }
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);
        $array = self::$items;

        foreach ($parts as $part) {
            if (!is_array($array) || !array_key_exists($part, $array)) {
                return $default;
            }
            $array = $array[$part];
        }

        return $array;
    }

    public static function set(string $key, mixed $value): void
    {
        $parts = explode('.', $key);
        $array = &self::$items;

        foreach ($parts as $i => $part) {
            if ($i === count($parts) - 1) {
                $array[$part] = $value;
            } else {
                if (!isset($array[$part]) || !is_array($array[$part])) {
                    $array[$part] = [];
                }
                $array = &$array[$part];
            }
        }
    }

    public static function all(): array
    {
        return self::$items;
    }
}
