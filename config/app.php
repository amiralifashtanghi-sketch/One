<?php
return [
    'app' => [
        'name' => 'EAFD — Digital Engineering & Growth System',
        'url' => 'http://localhost',
        'env' => 'production',
        'timezone' => 'Asia/Tehran',
        'charset' => 'UTF-8',
        'lang' => 'fa',
        'dir' => 'rtl',
        'version' => '1.0.0',
    ],
    'db' => [
        'host' => 'localhost',
        'name' => 'eafd_db',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
        'prefix' => 'eafd_',
    ],
    'security' => [
        'session_name' => 'EAFD_SESSID',
        'session_lifetime' => 7200,
        'hash_algo' => PASSWORD_ARGON2ID,
    ]
];
