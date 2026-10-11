<?php

namespace App\Core;

class Response {
    public static function json(array $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    public static function render(string $view, array $data = []): void {
        $viewFile = __DIR__ . '/../../views/' . str_replace('.', '/', $view) . '.php';
        if (!file_exists($viewFile)) {
            http_response_code(404);
            echo "قالب مورد نظر پیدا نشد: " . htmlspecialchars($view);
            exit;
        }

        extract($data);
        require $viewFile;
        exit;
    }

    public static function redirect(string $url): void {
        header("Location: " . $url);
        exit;
    }

    public static function status(int $code): void {
        http_response_code($code);
    }
}
