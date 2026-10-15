<?php

namespace App\Core;

class RBAC
{
    public const ROLE_ADMIN = 'admin';
    public const ROLE_OPERATOR = 'operator';
    public const ROLE_CUSTOMER = 'customer';
    public const ROLE_GUEST = 'guest';

    protected static array $permissions = [
        self::ROLE_ADMIN => [
            'manage_users',
            'manage_settings',
            'manage_design_studio',
            'manage_pages',
            'manage_services',
            'manage_projects',
            'manage_products',
            'manage_orders',
            'manage_licenses',
            'manage_tools',
            'manage_quizzes',
            'view_logs',
            'access_admin',
        ],
        self::ROLE_OPERATOR => [
            'manage_pages',
            'manage_services',
            'manage_projects',
            'manage_orders',
            'manage_licenses',
            'access_admin',
        ],
        self::ROLE_CUSTOMER => [
            'view_account',
            'view_orders',
            'view_licenses',
            'download_products',
        ],
        self::ROLE_GUEST => [
            'view_public',
        ],
    ];

    public static function can(string $permission): bool
    {
        $role = Auth::role();
        $allowed = self::$permissions[$role] ?? [];
        return in_array($permission, $allowed, true);
    }

    public static function checkPermission(string $permission): void
    {
        if (!self::can($permission)) {
            ErrorHandler::renderErrorPage(403, "عدم دسترسی کافی", "شما مجوز لازم برای دسترسی به این بخش را ندارید.");
            exit(0);
        }
    }
}
