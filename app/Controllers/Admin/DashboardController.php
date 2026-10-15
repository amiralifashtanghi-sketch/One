<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\SessionManager;
use App\Core\Database;

class DashboardController extends Controller {
    private function checkAuth(): void {
        SessionManager::start();
        if (!SessionManager::get('admin_user')) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void {
        $this->checkAuth();

        $stats = [
            'services_count' => 0,
            'portfolio_count' => 0,
            'leads_count' => 0,
            'faqs_count' => 0,
        ];

        try {
            $stats['services_count'] = Database::fetch("SELECT COUNT(*) as c FROM eafd_services")['c'] ?? 0;
            $stats['portfolio_count'] = Database::fetch("SELECT COUNT(*) as c FROM eafd_portfolio")['c'] ?? 0;
            $stats['leads_count'] = Database::fetch("SELECT COUNT(*) as c FROM eafd_leads")['c'] ?? 0;
            $stats['faqs_count'] = Database::fetch("SELECT COUNT(*) as c FROM eafd_faqs")['c'] ?? 0;

            $recentLeads = Database::fetchAll("SELECT * FROM eafd_leads ORDER BY id DESC LIMIT 5");
        } catch (\Exception $e) {
            $recentLeads = [];
        }

        $this->render('admin/dashboard', [
            'pageTitle' => 'داشبورد مدیریتی EAFD',
            'stats' => $stats,
            'recentLeads' => $recentLeads,
            'adminUser' => SessionManager::get('admin_user')
        ], 'admin');
    }
}
