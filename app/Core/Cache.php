<?php

namespace App\Core;

class Cache
{
    protected static string $cacheDir = __DIR__ . '/../../storage/cache/';

    protected static function getFilePath(string $key): string
    {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }
        return self::$cacheDir . md5($key) . '.cache';
    }

    public static function set(string $key, mixed $value, int $ttl = 3600): void
    {
        $file = self::getFilePath($key);
        $data = [
            'expires_at' => time() + $ttl,
            'content' => $value,
        ];

        file_put_contents($file, serialize($data), LOCK_EX);
    }

    public static function get(string $key, mixed $default = null): mixed
    {
        $file = self::getFilePath($key);

        if (!file_exists($file)) {
            return $default;
        }

        $content = file_get_contents($file);
        if ($content === false) {
            return $default;
        }

        $data = @unserialize($content);
        if (!is_array($data) || !isset($data['expires_at'])) {
            return $default;
        }

        if (time() > $data['expires_at']) {
            @unlink($file);
            return $default;
        }

        return $data['content'];
    }

    public static function delete(string $key): bool
    {
        $file = self::getFilePath($key);
        if (file_exists($file)) {
            return unlink($file);
        }
        return false;
    }

    public static function flush(): void
    {
        if (!is_dir(self::$cacheDir)) {
            return;
        }

        $files = glob(self::$cacheDir . '*.cache');
        foreach ($files as $file) {
            @unlink($file);
        }
    }
}
