<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security;
use App\Core\SessionManager;
use App\Core\Database;

class SeoController extends Controller {
    private function checkAuth(): void {
        SessionManager::start();
        if (!SessionManager::get('admin_user')) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void {
        $this->checkAuth();
        try {
            $settingsRaw = Database::fetchAll("SELECT * FROM eafd_settings");
        } catch (\Exception $e) {
            $settingsRaw = [];
        }
        $settings = [];
        foreach ($settingsRaw as $s) {
            $settings[$s['setting_key']] = $s['setting_value'];
        }

        $this->render('admin/seo/index', [
            'pageTitle' => 'تنظیمات ساختاری سئو و متاداده‌ها',
            'settings' => $settings,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function update(): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/seo');
        }

        $fields = ['site_title', 'site_description', 'contact_email', 'contact_phone', 'primary_color', 'accent_color'];
        foreach ($fields as $f) {
            if (isset($_POST[$f])) {
                $val = Security::sanitizeString($_POST[$f]);
                Database::query("INSERT INTO eafd_settings (setting_key, setting_value) VALUES (:k, :v) ON DUPLICATE KEY UPDATE setting_value = :v2", [
                    'k' => $f, 'v' => $val, 'v2' => $val
                ]);
            }
        }

        $this->redirect('/admin/seo');
    }
}
