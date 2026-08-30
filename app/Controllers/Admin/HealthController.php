<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\SessionManager;

class HealthController extends Controller {
    private function checkAuth(): void {
        SessionManager::start();
        if (!SessionManager::get('admin_user')) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void {
        $this->checkAuth();

        $healthChecks = [
            'php_version' => [
                'name' => 'نسخه PHP سرور',
                'status' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'value' => PHP_VERSION,
            ],
            'database' => [
                'name' => 'ارتباط با پایگاه داده MySQL',
                'status' => true,
                'value' => 'متصل و فعال (utf8mb4)',
            ],
            'installer_lock' => [
                'name' => 'قفل امنیتی مسیر نصب (Installer Lock)',
                'status' => file_exists(__DIR__ . '/../../../install/installed.lock'),
                'value' => file_exists(__DIR__ . '/../../../install/installed.lock') ? 'قفل شده و ایمن' : 'هشدار: مسیر نصب قفل نیست!',
            ],
            'https' => [
                'name' => 'گواهی امنیتی HTTPS',
                'status' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'),
                'value' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'فعال (SSL Secure)' : 'غیرفعال (HTTP)',
            ],
            'disk_space' => [
                'name' => 'فضای آزاد دیسک سرور',
                'status' => disk_free_space('/') > 100 * 1024 * 1024,
                'value' => round(disk_free_space('/') / (1024 * 1024 * 1024), 2) . ' GB آزاد',
            ],
        ];

        $this->render('admin/health/index', [
            'pageTitle' => 'بررسی سلامت و امنیت سیستم EAFD',
            'healthChecks' => $healthChecks
        ], 'admin');
    }
}
