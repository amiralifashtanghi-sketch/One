<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security;
use App\Core\SessionManager;
use App\Core\Database;

class LabController extends Controller {
    private function checkAuth(): void {
        SessionManager::start();
        if (!SessionManager::get('admin_user')) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void {
        $this->checkAuth();
        try {
            $labs = Database::fetchAll("SELECT * FROM eafd_lab ORDER BY id DESC");
        } catch (\Exception $e) {
            $labs = [];
        }
        $this->render('admin/lab/index', [
            'pageTitle' => 'مدیریت آزمایشگاه EAFD (LAB)',
            'labs' => $labs
        ], 'admin');
    }

    public function create(): void {
        $this->checkAuth();
        $this->render('admin/lab/form', [
            'pageTitle' => 'تعریف پروژه آزمایشگاهی جدید',
            'lab' => null,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function store(): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/lab');
        }

        $title = Security::sanitizeString($_POST['title'] ?? '');
        $description = Security::sanitizeString($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'experimental';
        $technology = Security::sanitizeString($_POST['technology'] ?? '');
        $demoUrl = Security::sanitizeString($_POST['demo_url'] ?? '');
        $githubUrl = Security::sanitizeString($_POST['github_url'] ?? '');

        if (!empty($title)) {
            Database::query(
                "INSERT INTO eafd_lab (title, description, status, technology, demo_url, github_url) VALUES (:t, :d, :st, :tech, :demo, :gh)",
                ['t' => $title, 'd' => $description, 'st' => $status, 'tech' => $technology, 'demo' => $demoUrl, 'gh' => $githubUrl]
            );
        }

        $this->redirect('/admin/lab');
    }

    public function edit(int $id): void {
        $this->checkAuth();
        $lab = Database::fetch("SELECT * FROM eafd_lab WHERE id = :id LIMIT 1", ['id' => $id]);
        if (!$lab) {
            $this->redirect('/admin/lab');
        }

        $this->render('admin/lab/form', [
            'pageTitle' => 'ویرایش پروژه آزمایشگاهی',
            'lab' => $lab,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function update(int $id): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/lab');
        }

        $title = Security::sanitizeString($_POST['title'] ?? '');
        $description = Security::sanitizeString($_POST['description'] ?? '');
        $status = $_POST['status'] ?? 'experimental';
        $technology = Security::sanitizeString($_POST['technology'] ?? '');
        $demoUrl = Security::sanitizeString($_POST['demo_url'] ?? '');
        $githubUrl = Security::sanitizeString($_POST['github_url'] ?? '');

        Database::query(
            "UPDATE eafd_lab SET title = :t, description = :d, status = :st, technology = :tech, demo_url = :demo, github_url = :gh WHERE id = :id",
            ['t' => $title, 'd' => $description, 'st' => $status, 'tech' => $technology, 'demo' => $demoUrl, 'gh' => $githubUrl, 'id' => $id]
        );

        $this->redirect('/admin/lab');
    }

    public function delete(int $id): void {
        $this->checkAuth();
        Database::query("DELETE FROM eafd_lab WHERE id = :id", ['id' => $id]);
        $this->redirect('/admin/lab');
    }
}
