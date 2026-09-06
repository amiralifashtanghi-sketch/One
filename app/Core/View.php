<?php

namespace App\Core;

class View
{
    protected static string $viewsDir = __DIR__ . '/../Views/';

    public static function render(string $viewPath, array $data = [], ?string $layout = 'main'): void
    {
        $viewFile = self::$viewsDir . str_replace('.', '/', $viewPath) . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception("View file {$viewFile} does not exist.");
        }

        extract($data);

        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        if ($layout === null) {
            echo $content;
            return;
        }

        $layoutFile = self::$viewsDir . 'layouts/' . $layout . '.php';
        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout file {$layoutFile} does not exist.");
        }

        require $layoutFile;
    }

    public static function partial(string $partialPath, array $data = []): void
    {
        $partialFile = self::$viewsDir . str_replace('.', '/', $partialPath) . '.php';

        if (!file_exists($partialFile)) {
            throw new \Exception("Partial file {$partialFile} does not exist.");
        }

        extract($data);
        require $partialFile;
    }
}
