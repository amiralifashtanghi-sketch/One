<?php

// Host Header Validation & Production HTTPS Origin Resolution
$allowedHosts = ['eafd.ir', 'www.eafd.ir', 'localhost', '127.0.0.1'];
$rawHost = strtolower($_SERVER['HTTP_HOST'] ?? 'eafd.ir');
// Strip port if present
$hostOnly = explode(':', $rawHost)[0];

if (!in_array($hostOnly, $allowedHosts, true)) {
    $hostOnly = 'eafd.ir';
}

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : (($hostOnly === 'localhost' || $hostOnly === '127.0.0.1') ? 'http' : 'https');
$baseUrl = getenv('APP_URL') ?: ($protocol . '://' . $hostOnly);

$appKey = getenv('APP_KEY');
if (empty($appKey)) {
    $keyFile = __DIR__ . '/../storage/app_key.secret';
    if (file_exists($keyFile)) {
        $appKey = trim(file_get_contents($keyFile));
    }
    if (empty($appKey)) {
        $appKey = 'eafd_sec_' . bin2hex(random_bytes(32));
        $storageDir = __DIR__ . '/../storage';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0755, true);
        }
        @file_put_contents($keyFile, $appKey, LOCK_EX);
    }
}

return [
    'name' => 'EAFD Platform',
    'url' => $baseUrl,
    'allowed_hosts' => $allowedHosts,
    'timezone' => 'Asia/Tehran',
    'security' => [
        'app_key' => $appKey,
        'csrf_token_name' => '_csrf_token',
    ],
    'version' => '1.0.0',
];
