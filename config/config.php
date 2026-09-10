<?php

// Dynamic Base URL Generator with HTTPS Detection
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
$baseUrl = getenv('APP_URL') ?: ($protocol . '://' . $host);

$appKey = getenv('APP_KEY') ?: 'eafd_prod_secret_' . md5($host . 'eafd_secure_salt');

return [
    'name' => 'EAFD Platform',
    'url' => $baseUrl,
    'timezone' => 'Asia/Tehran',
    'security' => [
        'app_key' => $appKey,
        'csrf_token_name' => '_csrf_token',
    ],
    'version' => '1.0.0',
];
