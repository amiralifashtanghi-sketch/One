<?php

namespace Install;

use PDO;
use Exception;
use App\Core\Database;
use App\Core\Auth;
use App\Core\Config;
use App\Core\Sanitizer;
use App\Core\Logger;

class Installer
{
    protected static function getBaseDir(): string
    {
        return defined('EAFD_BASE_DIR') ? EAFD_BASE_DIR : dirname(__DIR__);
    }

    public static function isInstalled(): bool
    {
        return file_exists(self::getBaseDir() . '/config/installed.lock');
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
        $baseDir = self::getBaseDir();
        $dirs = [
            'storage' => $baseDir . '/storage',
            'storage/cache' => $baseDir . '/storage/cache',
            'storage/logs' => $baseDir . '/storage/logs',
            'storage/products' => $baseDir . '/storage/products',
            'config' => $baseDir . '/config',
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
                $path = $dbConfig['sqlite_path'] ?? (self::getBaseDir() . '/storage/database.sqlite');
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

    public static function runMigrationsAndSeeds(): array
    {
        $baseDir = self::getBaseDir();
        Logger::info("INSTALL_STEP_4_STARTED");

        try {
            $pdo = Database::getConnection();
            $driver = Config::get('database.driver', 'sqlite');
            Logger::info("DATABASE_CONNECTED", ['driver' => $driver]);

            // Create migrations history table if not exists
            if ($driver === 'mysql') {
                $pdo->exec("CREATE TABLE IF NOT EXISTS schema_migrations (id INT AUTO_INCREMENT PRIMARY KEY, migration VARCHAR(255) NOT NULL, executed_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
            } else {
                $pdo->exec("CREATE TABLE IF NOT EXISTS schema_migrations (id INTEGER PRIMARY KEY AUTOINCREMENT, migration VARCHAR(255) NOT NULL, executed_at DATETIME DEFAULT CURRENT_TIMESTAMP)");
            }

            $executedStmt = $pdo->query("SELECT migration FROM schema_migrations");
            $executedMigrations = $executedStmt ? $executedStmt->fetchAll(PDO::FETCH_COLUMN) : [];

            $files = glob($baseDir . '/config/migrations/*.sql');
            sort($files);

            foreach ($files as $file) {
                $fileName = basename($file);
                if (in_array($fileName, $executedMigrations)) {
                    continue;
                }

                Logger::info("MIGRATION_STARTED: {$fileName}");

                $sql = file_get_contents($file);
                if ($sql) {
                    // Translate auto-increment syntax for MySQL vs SQLite
                    if ($driver === 'mysql') {
                        $sql = str_replace('AUTOINCREMENT', 'AUTO_INCREMENT', $sql);
                        $sql = str_replace('INTEGER PRIMARY KEY AUTO_INCREMENT', 'INT AUTO_INCREMENT PRIMARY KEY', $sql);
                    }
                    $pdo->exec($sql);
                }

                $logStmt = $pdo->prepare("INSERT INTO schema_migrations (migration) VALUES (?)");
                $logStmt->execute([$fileName]);

                Logger::info("MIGRATION_COMPLETED: {$fileName}");
            }

            Logger::info("SEED_STARTED");
            $seedSql = file_get_contents($baseDir . '/config/seeds.sql');
            if ($seedSql) {
                $statements = array_filter(array_map('trim', explode(';', $seedSql)));
                foreach ($statements as $stmt) {
                    if (!empty($stmt)) {
                        $pdo->exec($stmt);
                    }
                }
            }
            Logger::info("SEED_COMPLETED");
            Logger::info("INSTALL_STEP_4_COMPLETED");

            return ['success' => true, 'message' => 'ساخت جداول و ثبت دیتای اولیه فارسی با موفقیت انجام شد.'];
        } catch (Exception $e) {
            Logger::error("INSTALL_STEP_4_FAILED: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'اجرای ساختار پایگاه داده با خطا مواجه شد: ' . $e->getMessage(),
            ];
        }
    }

    public static function createAdminAccount(string $name, string $email, string $phone, string $password): bool
    {
        try {
            $passHash = Auth::hashPassword($password);
            $phone = Sanitizer::cleanPhone($phone);

            $existing = Database::fetch("SELECT * FROM users WHERE id = 1");
            if ($existing) {
                Database::query(
                    "UPDATE users SET name = ?, email = ?, phone = ?, password_hash = ? WHERE id = 1",
                    [$name, $email, $phone, $passHash]
                );
            } else {
                Database::query(
                    "INSERT INTO users (id, name, email, phone, password_hash, role, is_active) VALUES (1, ?, ?, ?, ?, 'admin', 1)",
                    [$name, $email, $phone, $passHash]
                );
            }
            return true;
        } catch (Exception $e) {
            Logger::error("INSTALL_STEP_5_FAILED: " . $e->getMessage());
            return false;
        }
    }

    public static function lockInstallation(): void
    {
        $lockFile = self::getBaseDir() . '/config/installed.lock';
        file_put_contents($lockFile, "Installed at " . date('Y-m-d H:i:s'));
    }
}
