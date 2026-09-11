<?php

namespace App\Core;

use PDO;
use PDOException;
use Exception;

class Database
{
    protected static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $driver = Config::get('database.driver', 'sqlite');

            try {
                if ($driver === 'sqlite') {
                    $sqlitePath = Config::get('database.sqlite_path', __DIR__ . '/../../storage/database.sqlite');
                    $dir = dirname($sqlitePath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    self::$instance = new PDO("sqlite:" . $sqlitePath, null, null, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);
                    self::$instance->exec("PRAGMA foreign_keys = ON;");
                } else {
                    $host = Config::get('database.host', '127.0.0.1');
                    $port = Config::get('database.port', '3306');
                    $dbname = Config::get('database.dbname', 'eafd_db');
                    $charset = Config::get('database.charset', 'utf8mb4');
                    $username = Config::get('database.username', 'root');
                    $password = Config::get('database.password', '');

                    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset={$charset}";
                    self::$instance = new PDO($dsn, $username, $password, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ]);
                }
            } catch (PDOException $e) {
                throw new Exception("Database Connection Error: " . $e->getMessage());
            }
        }

        return self::$instance;
    }

    public static function query(string $sql, array $params = []): \PDOStatement
    {
        $stmt = self::getConnection()->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    public static function fetchAll(string $sql, array $params = []): array
    {
        return self::query($sql, $params)->fetchAll();
    }

    public static function fetch(string $sql, array $params = []): array|false
    {
        return self::query($sql, $params)->fetch();
    }

    public static function lastInsertId(): string|false
    {
        return self::getConnection()->lastInsertId();
    }

    public static function beginTransaction(): bool
    {
        return self::getConnection()->beginTransaction();
    }

    public static function commit(): bool
    {
        return self::getConnection()->commit();
    }

    public static function rollBack(): bool
    {
        return self::getConnection()->rollBack();
    }
}
