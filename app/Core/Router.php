<?php

namespace App\Core;

class Router {
    private static array $routes = [
        'GET' => [],
        'POST' => []
    ];

    private static array $reservedPrefixes = [
        'install', 'login', 'register', 'logout', 'forgot-password', 'reset-password',
        'admin', 'seller', 'dashboard', 'api', 'assets', 'uploads'
    ];

    public static function get(string $path, callable|array $handler, array $middlewares = []): void {
        self::$routes['GET'][self::normalizePath($path)] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public static function post(string $path, callable|array $handler, array $middlewares = []): void {
        self::$routes['POST'][self::normalizePath($path)] = [
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    private static function normalizePath(string $path): string {
        return '/' . trim($path, '/');
    }

    public static function isReserved(string $slug): bool {
        $firstPart = explode('/', trim($slug, '/'))[0] ?? '';
        return in_array(strtolower($firstPart), self::$reservedPrefixes);
    }

    public static function dispatch(string $uri, string $method): void {
        $uri = self::normalizePath($uri);
        $method = strtoupper($method);

        // Direct exact match
        if (isset(self::$routes[$method][$uri])) {
            $route = self::$routes[$method][$uri];
            self::executeRoute($route, []);
            return;
        }

        // Regex match for parameterized routes e.g. /admin/cards/edit/{id}
        foreach (self::$routes[$method] ?? [] as $routePath => $route) {
            $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $routePath);
            $pattern = '#^' . $pattern . '$#u';

            if (preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                self::executeRoute($route, $params);
                return;
            }
        }

        // Fallback for root single-segment slug /{slug} (User bio username or unassigned card code)
        $trimmed = trim($uri, '/');
        if (!empty($trimmed) && strpos($trimmed, '/') === false && !self::isReserved($trimmed)) {
            // Check fallback bio viewer handler
            if (isset(self::$routes['GET']['/{slug}'])) {
                self::executeRoute(self::$routes['GET']['/{slug}'], ['slug' => $trimmed]);
                return;
            }
        }

        // 404 Not Found
        Response::status(404);
        Response::render('404', ['title' => 'صفحه مورد نظر پیدا نشد']);
    }

    private static function executeRoute(array $route, array $params): void {
        // Run Middlewares if any
        foreach ($route['middlewares'] as $middleware) {
            if (is_callable($middleware)) {
                $middleware();
            } elseif (is_string($middleware) && class_exists($middleware)) {
                $m = new $middleware();
                $m->handle();
            }
        }

        $handler = $route['handler'];

        if (is_callable($handler)) {
            call_user_func_array($handler, $params);
        } elseif (is_array($handler) && count($handler) === 2) {
            [$controllerClass, $method] = $handler;
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $method)) {
                    call_user_func_array([$controller, $method], $params);
                    return;
                }
            }
            throw new \Exception("Controller method {$controllerClass}::{$method} does not exist.");
        }
    }
}
