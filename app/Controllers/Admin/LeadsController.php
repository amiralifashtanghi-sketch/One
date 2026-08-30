<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security;
use App\Core\SessionManager;
use App\Core\Database;

class LeadsController extends Controller {
    private function checkAuth(): void {
        SessionManager::start();
        if (!SessionManager::get('admin_user')) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void {
        $this->checkAuth();
        try {
            $leads = Database::fetchAll("SELECT * FROM eafd_leads ORDER BY id DESC");
        } catch (\Exception $e) {
            $leads = [];
        }
        $this->render('admin/leads/index', [
            'pageTitle' => 'مدیریت درخواست‌های پروژه (Leads)',
            'leads' => $leads
        ], 'admin');
    }

    public function show(int $id): void {
        $this->checkAuth();
        $lead = Database::fetch("SELECT * FROM eafd_leads WHERE id = :id LIMIT 1", ['id' => $id]);
        if (!$lead) {
            $this->redirect('/admin/leads');
        }

        $this->render('admin/leads/show', [
            'pageTitle' => 'بررسی درخواست ' . $lead['name'],
            'lead' => $lead,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function updateStatus(int $id): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/leads');
        }

        $status = $_POST['status'] ?? 'new';
        Database::query("UPDATE eafd_leads SET status = :st WHERE id = :id", ['st' => $status, 'id' => $id]);

        $this->redirect('/admin/leads/show/' . $id);
    }

    public function delete(int $id): void {
        $this->checkAuth();
        Database::query("DELETE FROM eafd_leads WHERE id = :id", ['id' => $id]);
        $this->redirect('/admin/leads');
    }
}
