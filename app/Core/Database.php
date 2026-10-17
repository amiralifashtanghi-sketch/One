<?php

namespace App\Core;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function connect(array $config): PDO {
        if (self::$instance === null) {
            $host = $config['host'] ?? 'localhost';
            $port = $config['port'] ?? 3306;
            $dbname = $config['dbname'] ?? '';
            $user = $config['username'] ?? '';
            $pass = $config['password'] ?? '';
            $charset = $config['charset'] ?? 'utf8mb4';

            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                error_log("Database Connection Error: " . $e->getMessage());
                throw new \Exception("خطا در اتصال به پایگاه داده. لطفاً تنظیمات دیتابیس را بررسی کنید.");
            }
        }

        return self::$instance;
    }

    public static function getInstance(): ?PDO {
        return self::$instance;
    }

    public static function query(string $sql, array $params = []): \PDOStatement {
        if (self::$instance === null) {
            throw new \Exception("دیتابیس متصل نشده است.");
        }
        $stmt = self::$instance->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetch(string $sql, array $params = []): ?array {
        $stmt = self::query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    public static function fetchAll(string $sql, array $params = []): array {
        $stmt = self::query($sql, $params);
        return $stmt->fetchAll();
    }

    public static function execute(string $sql, array $params = []): bool {
        $stmt = self::query($sql, $params);
        return $stmt->rowCount() > 0;
    }

    public static function lastInsertId(): string {
        return self::$instance->lastInsertId();
    }

    public static function beginTransaction(): bool {
        return self::$instance->beginTransaction();
    }

    public static function commit(): bool {
        return self::$instance->commit();
    }

    public static function rollBack(): bool {
        return self::$instance->rollBack();
    }
}
