<?php

namespace App\Core;

class Router
{
    protected array $routes = [];
    protected array $namedRoutes = [];

    public function get(string $path, array|callable $handler, array $middleware = [], string $name = ''): void
    {
        $this->addRoute('GET', $path, $handler, $middleware, $name);
    }

    public function post(string $path, array|callable $handler, array $middleware = [], string $name = ''): void
    {
        $this->addRoute('POST', $path, $handler, $middleware, $name);
    }

    public function put(string $path, array|callable $handler, array $middleware = [], string $name = ''): void
    {
        $this->addRoute('PUT', $path, $handler, $middleware, $name);
    }

    public function delete(string $path, array|callable $handler, array $middleware = [], string $name = ''): void
    {
        $this->addRoute('DELETE', $path, $handler, $middleware, $name);
    }

    protected function addRoute(string $method, string $path, array|callable $handler, array $middleware, string $name): void
    {
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[a-zA-Z0-9_-]+)', $path);
        $pattern = '#^' . $pattern . '$#';

        $route = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'handler' => $handler,
            'middleware' => $middleware,
        ];

        $this->routes[] = $route;

        if (!empty($name)) {
            $this->namedRoutes[$name] = $path;
        }
    }

    public function dispatch(Request $request): void
    {
        $uri = $request->uri();
        $method = $request->method();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                Middleware::run($route['middleware'], $request);

                $handler = $route['handler'];

                if (is_callable($handler)) {
                    call_user_func_array($handler, [$request, $params]);
                    return;
                }

                if (is_array($handler)) {
                    [$controllerClass, $action] = $handler;
                    if (!class_exists($controllerClass)) {
                        throw new \Exception("Controller {$controllerClass} not found.");
                    }
                    $controller = new $controllerClass();
                    if (!method_exists($controller, $action)) {
                        throw new \Exception("Action {$action} not found in controller {$controllerClass}.");
                    }
                    call_user_func_array([$controller, $action], [$request, $params]);
                    return;
                }
            }
        }

        ErrorHandler::renderErrorPage(404, "صفحه مورد نظر پیدا نشد (۴۰۴)", "آدرس وارد شده در سامانه وجود ندارد یا منتقل شده است.");
    }

    public function url(string $name, array $params = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            return '/';
        }

        $path = $this->namedRoutes[$name];
        foreach ($params as $key => $value) {
            $path = str_replace("{{$key}}", (string)$value, $path);
        }

        return $path;
    }
}
