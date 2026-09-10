<?php

namespace App\Core;

use Throwable;

class ErrorHandler
{
    public static function register(): void
    {
        error_reporting(E_ALL);
        set_exception_handler([self::class, 'handleException']);
        set_error_handler([self::class, 'handleError']);
        register_shutdown_function([self::class, 'handleShutdown']);
    }

    public static function handleError(int $errno, string $errstr, string $errfile, int $errline): bool
    {
        if (!(error_reporting() & $errno)) {
            return false;
        }

        self::log("PHP Error [{$errno}]: {$errstr} in {$errfile} on line {$errline}");

        if ($errno === E_USER_ERROR || $errno === E_RECOVERABLE_ERROR) {
            self::renderErrorPage(500, "خطای داخلی سرور", "خطایی در هنگام پردازش درخواست رخ داده است.");
            exit(1);
        }

        return true;
    }

    public static function handleException(Throwable $e): void
    {
        self::log("Uncaught Exception: " . $e->getMessage() . "\nTrace: " . $e->getTraceAsString());
        self::renderErrorPage(500, "خطای سامانه EAFD", "خطایی در هنگام پردازش درخواست رخ داده است. لطفاً بعداً تلاش کنید.");
        exit(1);
    }

    public static function handleShutdown(): void
    {
        $error = error_get_last();
        if ($error !== null && in_array($error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
            self::log("Fatal Error [{$error['type']}]: {$error['message']} in {$error['file']} on line {$error['line']}");
            self::renderErrorPage(500, "خطای بحرانی سرور", "سیستم با خطای بحرانی مواجه شده است.");
        }
    }

    public static function log(string $message): void
    {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }
        $logFile = $logDir . '/app.log';
        $timestamp = date('Y-m-d H:i:s');
        file_put_contents($logFile, "[{$timestamp}] {$message}\n", FILE_APPEND | LOCK_EX);
    }

    public static function renderErrorPage(int $statusCode, string $title, string $message): void
    {
        if (!headers_sent()) {
            http_response_code($statusCode);
            header('Content-Type: text/html; charset=UTF-8');
        }

        echo <<<HTML
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{$title} - پلتفرم EAFD</title>
    <style>
        body {
            background-color: #0d1117;
            color: #c9d1d9;
            font-family: Tahoma, 'Segoe UI', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .error-card {
            background: #161b22;
            border: 1px solid #30363d;
            border-radius: 12px;
            padding: 40px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            text-align: center;
        }
        h1 {
            color: #f85149;
            font-size: 1.8rem;
            margin-bottom: 15px;
        }
        p {
            font-size: 1rem;
            line-height: 1.6;
            color: #8b949e;
            margin-bottom: 25px;
        }
        a {
            display: inline-block;
            background: #238636;
            color: #ffffff;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            transition: background 0.2s;
        }
        a:hover {
            background: #2ea043;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <h1>{$title}</h1>
        <p>{$message}</p>
        <a href="/">بازگشت به صفحه اصلی</a>
    </div>
</body>
</html>
HTML;
    }
}
