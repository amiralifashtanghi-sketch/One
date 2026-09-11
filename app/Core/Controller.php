<?php

namespace App\Core;

abstract class Controller
{
    protected Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    protected function model(string $modelClass): object
    {
        $fullClass = "\\App\\Models\\" . $modelClass;
        if (class_exists($fullClass)) {
            return new $fullClass();
        }
        throw new \Exception("Model class {$fullClass} not found.");
    }

    protected function render(string $viewPath, array $data = [], ?string $layout = 'main'): void
    {
        View::render($viewPath, $data, $layout);
    }

    protected function json(mixed $data, int $statusCode = 200): void
    {
        Response::json($data, $statusCode);
    }

    protected function redirect(string $url): void
    {
        Response::redirect($url);
    }
}
