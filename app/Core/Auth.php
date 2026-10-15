<?php

namespace App\Core;

class Auth
{
    protected static string $userKey = '_auth_user';

    public static function hashPassword(string $password): string
    {
        if (defined('PASSWORD_ARGON2ID')) {
            return password_hash($password, PASSWORD_ARGON2ID);
        }
        return password_hash($password, PASSWORD_BCRYPT);
    }

    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public static function attempt(string $identifier, string $password): bool|array
    {
        $identifier = Sanitizer::cleanPhone($identifier);

        $user = Database::fetch(
            "SELECT * FROM users WHERE (phone = ? OR email = ?) AND is_active = 1 LIMIT 1",
            [$identifier, $identifier]
        );

        if (!$user) {
            return false;
        }

        if (!self::verifyPassword($password, $user['password_hash'])) {
            return false;
        }

        self::login($user);
        return $user;
    }

    public static function login(array $user): void
    {
        Session::start();
        session_regenerate_id(true);

        unset($user['password_hash']);
        Session::set(self::$userKey, $user);

        Logger::info("کاربر با شناسه {$user['id']} وارد گردید.", ['user_id' => $user['id']]);
    }

    public static function logout(): void
    {
        Session::remove(self::$userKey);
        Session::destroy();
    }

    public static function user(): ?array
    {
        return Session::get(self::$userKey);
    }

    public static function check(): bool
    {
        return self::user() !== null;
    }

    public static function id(): ?int
    {
        $user = self::user();
        return $user ? (int)$user['id'] : null;
    }

    public static function role(): string
    {
        $user = self::user();
        return $user['role'] ?? 'guest';
    }
}
