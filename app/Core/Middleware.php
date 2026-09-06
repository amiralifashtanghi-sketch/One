<?php

namespace App\Core;

class Middleware
{
    protected static array $map = [
        'auth' => \App\Middleware\AuthMiddleware::class,
        'admin' => \App\Middleware\AdminMiddleware::class,
        'csrf' => \App\Middleware\CsrfMiddleware::class,
        'guest' => \App\Middleware\GuestMiddleware::class,
    ];

    public static function run(array $middlewareList, Request $request): void
    {
        foreach ($middlewareList as $name) {
            $class = self::$map[$name] ?? $name;
            if (class_exists($class)) {
                $instance = new $class();
                if (method_exists($instance, 'handle')) {
                    $instance->handle($request);
                }
            }
        }
    }
}
