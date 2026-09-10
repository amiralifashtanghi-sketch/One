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

$appKey = getenv('APP_KEY') ?: 'eafd_sec_key_' . hash('sha256', $hostOnly . 'eafd_prod_salt_v1');

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
