<?php
namespace App\Core;

class Router {
    private array $routes = [];

    public function add(string $method, string $path, $handler, array $middlewares = []): void {
        $path = '/' . trim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }

        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<\1>[a-zA-Z0-9_\-]+)', $path);
        $pattern = '#^' . $pattern . '$#u';

        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middlewares' => $middlewares
        ];
    }

    public function get(string $path, $handler, array $middlewares = []): void {
        $this->add('GET', $path, $handler, $middlewares);
    }

    public function post(string $path, $handler, array $middlewares = []): void {
        $this->add('POST', $path, $handler, $middlewares);
    }

    public function dispatch(string $method, string $uri) {
        $basePath = Security::getBaseUrl();
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // Strip subfolder prefix if hosted under public_html subfolder
        if (!empty($basePath) && strpos($path, $basePath) === 0) {
            $path = substr($path, strlen($basePath));
        }

        $path = '/' . trim($path, '/');
        if ($path !== '/') {
            $path = rtrim($path, '/');
        }
        $method = strtoupper($method);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['pattern'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                foreach ($route['middlewares'] as $middleware) {
                    $mw = new $middleware();
                    $response = $mw->handle();
                    if ($response === false) {
                        return;
                    }
                }

                $handler = $route['handler'];
                if (is_callable($handler)) {
                    return call_user_func_array($handler, $params);
                }

                if (is_array($handler)) {
                    [$class, $action] = $handler;
                    $controller = new $class();
                    return call_user_func_array([$controller, $action], $params);
                }
            }
        }

        http_response_code(404);
        if (file_exists(__DIR__ . '/../Views/pages/404.php')) {
            $pageTitle = '۴۰۴ - صفحه یافت نشد';
            $metaDescription = 'صفحه مورد نظر در سیستم EAFD یافت نشد.';
            ob_start();
            require_once __DIR__ . '/../Views/pages/404.php';
            $content = ob_get_clean();
            require_once __DIR__ . '/../Views/layouts/main.php';
        } else {
            echo "۴۰۴ - صفحه مورد نظر یافت نشد.";
        }
    }
}
