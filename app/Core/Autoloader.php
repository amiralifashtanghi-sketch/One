<?php

namespace App\Core;

class Autoloader
{
    protected static string $baseDir;

    public static function register(string $baseDir): void
    {
        self::$baseDir = rtrim($baseDir, '/\\') . DIRECTORY_SEPARATOR;

        spl_autoload_register([self::class, 'loadClass']);
    }

    public static function loadClass(string $class): bool
    {
        $prefix = 'App\\';

        if (str_starts_with($class, $prefix)) {
            $relativeClass = substr($class, strlen($prefix));
            $file = self::$baseDir . 'app' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }

        if (str_starts_with($class, 'Install\\')) {
            $relativeClass = substr($class, strlen('Install\\'));
            $file = self::$baseDir . 'install' . DIRECTORY_SEPARATOR . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

            if (file_exists($file)) {
                require_once $file;
                return true;
            }
        }

        return false;
    }
}
