<?php

namespace App\Core;

class Csrf
{
    protected static string $sessionKey = '_csrf_token';

    public static function generate(): string
    {
        Session::start();
        $token = Session::get(self::$sessionKey);

        if (!$token) {
            $token = bin2hex(random_bytes(32));
            Session::set(self::$sessionKey, $token);
        }

        return $token;
    }

    public static function verify(?string $token): bool
    {
        Session::start();
        $sessionToken = Session::get(self::$sessionKey);

        if (!$sessionToken || !$token) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function input(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="' . self::$sessionKey . '" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function getTokenName(): string
    {
        return self::$sessionKey;
    }
}
