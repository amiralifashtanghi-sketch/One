<?php
namespace App\Core;

class Controller {
    protected function render(string $view, array $data = [], string $layout = 'main'): void {
        extract($data);

        $viewFile = __DIR__ . '/../Views/' . $view . '.php';
        $layoutFile = __DIR__ . '/../Views/layouts/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("نمای {$view} یافت نشد.");
        }

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout && file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }

    protected function json(array $data, int $statusCode = 200): void {
        if (ob_get_length()) {
            ob_clean();
        }
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit;
    }

    protected function redirect(string $url): void {
        if (ob_get_length()) {
            ob_clean();
        }
        $baseUrl = Security::getBaseUrl();
        if (strpos($url, 'http') !== 0 && !empty($baseUrl) && strpos($url, $baseUrl) !== 0) {
            $url = $baseUrl . '/' . ltrim($url, '/');
        }
        header('Location: ' . $url);
        exit;
    }
}
