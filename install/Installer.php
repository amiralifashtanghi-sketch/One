<?php

namespace Install;

use PDO;
use Exception;

class Installer
{
    public static function isInstalled(): bool
    {
        return file_exists(__DIR__ . '/../config/installed.lock');
    }

    public static function checkEnvironment(): array
    {
        return [
            'php_version' => [
                'name' => 'نسخه PHP 8.2 یا بالاتر',
                'pass' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'value' => PHP_VERSION,
            ],
            'pdo' => [
                'name' => 'افزونه PDO',
                'pass' => extension_loaded('pdo'),
                'value' => extension_loaded('pdo') ? 'فعال' : 'غیرفعال',
            ],
            'mbstring' => [
                'name' => 'افزونه Multibyte String (mbstring)',
                'pass' => extension_loaded('mbstring'),
                'value' => extension_loaded('mbstring') ? 'فعال' : 'غیرفعال',
            ],
            'curl' => [
                'name' => 'افزونه cURL',
                'pass' => extension_loaded('curl'),
                'value' => extension_loaded('curl') ? 'فعال' : 'غیرفعال',
            ],
            'json' => [
                'name' => 'افزونه JSON',
                'pass' => extension_loaded('json'),
                'value' => extension_loaded('json') ? 'فعال' : 'غیرفعال',
            ],
        ];
    }

    public static function checkPermissions(): array
    {
        $dirs = [
            'storage' => __DIR__ . '/../storage',
            'storage/cache' => __DIR__ . '/../storage/cache',
            'storage/logs' => __DIR__ . '/../storage/logs',
            'storage/products' => __DIR__ . '/../storage/products',
            'config' => __DIR__ . '/../config',
        ];

        $results = [];
        foreach ($dirs as $key => $path) {
            if (!is_dir($path)) {
                @mkdir($path, 0777, true);
            }
            $writable = is_writable($path);
            $results[$key] = [
                'name' => "پوشه {$key}",
                'pass' => $writable,
                'path' => $path,
            ];
        }

        return $results;
    }

    public static function testDbConnection(array $dbConfig): array
    {
        try {
            $driver = $dbConfig['driver'] ?? 'sqlite';
            if ($driver === 'sqlite') {
                $path = $dbConfig['sqlite_path'] ?? __DIR__ . '/../storage/database.sqlite';
                $pdo = new PDO("sqlite:" . $path);
            } else {
                $dsn = "mysql:host={$dbConfig['host']};port={$dbConfig['port']};dbname={$dbConfig['dbname']};charset=utf8mb4";
                $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                ]);
            }
            return ['success' => true, 'message' => 'اتصال به پایگاه داده با موفقیت برقرار شد.'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => 'خطا در اتصال: ' . $e->getMessage()];
        }
    }

    public static function runMigrationsAndSeeds(): bool
    {
        try {
            $files = glob(__DIR__ . '/../config/migrations/*.sql');
            sort($files);
            foreach ($files as $file) {
                $sql = file_get_contents($file);
                \App\Core\Database::getConnection()->exec($sql);
            }

            $seedSql = file_get_contents(__DIR__ . '/../config/seeds.sql');
            \App\Core\Database::getConnection()->exec($seedSql);

            return true;
        } catch (Exception $e) {
            \App\Core\ErrorHandler::log("Installer Migration Error: " . $e->getMessage());
            return false;
        }
    }

    public static function createAdminAccount(string $name, string $email, string $phone, string $password): bool
    {
        try {
            $passHash = \App\Core\Auth::hashPassword($password);
            $phone = \App\Core\Sanitizer::cleanPhone($phone);

            $existing = \App\Core\Database::fetch("SELECT * FROM users WHERE id = 1");
            if ($existing) {
                \App\Core\Database::query(
                    "UPDATE users SET name = ?, email = ?, phone = ?, password_hash = ? WHERE id = 1",
                    [$name, $email, $phone, $passHash]
                );
            } else {
                \App\Core\Database::query(
                    "INSERT INTO users (id, name, email, phone, password_hash, role, is_active) VALUES (1, ?, ?, ?, ?, 'admin', 1)",
                    [$name, $email, $phone, $passHash]
                );
            }
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function lockInstallation(): void
    {
        $lockFile = __DIR__ . '/../config/installed.lock';
        file_put_contents($lockFile, "Installed at " . date('Y-m-d H:i:s'));
    }
}
