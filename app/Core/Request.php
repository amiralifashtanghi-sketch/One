<?php

namespace App\Core;

class Request
{
    protected array $query;
    protected array $post;
    protected array $files;
    protected array $server;
    protected ?array $jsonPayload = null;

    public function __construct()
    {
        $this->query = Sanitizer::clean($_GET);
        $this->post = Sanitizer::clean($_POST);
        $this->files = $_FILES;
        $this->server = $_SERVER;

        $contentType = $this->server['CONTENT_TYPE'] ?? $this->server['HTTP_CONTENT_TYPE'] ?? '';
        if (str_contains($contentType, 'application/json')) {
            $input = file_get_contents('php://input');
            $decoded = json_decode($input, true);
            if (is_array($decoded)) {
                $this->jsonPayload = Sanitizer::clean($decoded);
            }
        }
    }

    public function uri(): string
    {
        $uri = strtok($this->server['REQUEST_URI'] ?? '/', '?');
        $uri = rawurldecode($uri);
        return '/' . trim($uri, '/');
    }

    public function method(): string
    {
        $method = strtoupper($this->server['REQUEST_METHOD'] ?? 'GET');
        if ($method === 'POST' && isset($this->post['_method'])) {
            return strtoupper($this->post['_method']);
        }
        return $method;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->query[$key] ?? $default;
    }

    public function post(string $key, mixed $default = null): mixed
    {
        if ($this->jsonPayload !== null) {
            return $this->jsonPayload[$key] ?? $default;
        }
        return $this->post[$key] ?? $default;
    }

    public function all(): array
    {
        if ($this->jsonPayload !== null) {
            return array_merge($this->query, $this->jsonPayload);
        }
        return array_merge($this->query, $this->post);
    }

    public function file(string $key): ?array
    {
        return $this->files[$key] ?? null;
    }

    public function header(string $key, ?string $default = null): ?string
    {
        $headerKey = 'HTTP_' . strtoupper(str_replace('-', '_', $key));
        return $this->server[$headerKey] ?? $default;
    }

    public function isAjax(): bool
    {
        return ($this->header('X-Requested-With') === 'XMLHttpRequest');
    }
}
