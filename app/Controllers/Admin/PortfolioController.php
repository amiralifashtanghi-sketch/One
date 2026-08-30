<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security;
use App\Core\SessionManager;
use App\Core\Database;

class PortfolioController extends Controller {
    private function checkAuth(): void {
        SessionManager::start();
        if (!SessionManager::get('admin_user')) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void {
        $this->checkAuth();
        try {
            $items = Database::fetchAll("SELECT * FROM eafd_portfolio ORDER BY id DESC");
        } catch (\Exception $e) {
            $items = [];
        }
        $this->render('admin/portfolio/index', [
            'pageTitle' => 'مدیریت نمونه‌کارها (بدون تصویر)',
            'items' => $items
        ], 'admin');
    }

    public function create(): void {
        $this->checkAuth();
        $this->render('admin/portfolio/form', [
            'pageTitle' => 'افزودن پروژه جدید',
            'item' => null,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function store(): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/portfolio');
        }

        $title = Security::sanitizeString($_POST['title'] ?? '');
        $slug = Security::sanitizeString($_POST['slug'] ?? '');
        $clientName = Security::sanitizeString($_POST['client_name'] ?? '');
        $serviceType = Security::sanitizeString($_POST['service_type'] ?? '');
        $technologies = Security::sanitizeString($_POST['technologies'] ?? '');
        $summary = Security::sanitizeString($_POST['summary'] ?? '');
        $challenge = $_POST['challenge'] ?? '';
        $solution = $_POST['solution'] ?? '';
        $results = $_POST['results'] ?? '';

        if (!empty($title) && !empty($slug)) {
            Database::query(
                "INSERT INTO eafd_portfolio (title, slug, client_name, service_type, technologies, summary, challenge, solution, results) VALUES (:t, :s, :c, :st, :tech, :sum, :chal, :sol, :res)",
                ['t' => $title, 's' => $slug, 'c' => $clientName, 'st' => $serviceType, 'tech' => $technologies, 'sum' => $summary, 'chal' => $challenge, 'sol' => $solution, 'res' => $results]
            );
        }

        $this->redirect('/admin/portfolio');
    }

    public function edit(int $id): void {
        $this->checkAuth();
        $item = Database::fetch("SELECT * FROM eafd_portfolio WHERE id = :id LIMIT 1", ['id' => $id]);
        if (!$item) {
            $this->redirect('/admin/portfolio');
        }

        $this->render('admin/portfolio/form', [
            'pageTitle' => 'ویرایش پروژه',
            'item' => $item,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function update(int $id): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/portfolio');
        }

        $title = Security::sanitizeString($_POST['title'] ?? '');
        $slug = Security::sanitizeString($_POST['slug'] ?? '');
        $clientName = Security::sanitizeString($_POST['client_name'] ?? '');
        $serviceType = Security::sanitizeString($_POST['service_type'] ?? '');
        $technologies = Security::sanitizeString($_POST['technologies'] ?? '');
        $summary = Security::sanitizeString($_POST['summary'] ?? '');
        $challenge = $_POST['challenge'] ?? '';
        $solution = $_POST['solution'] ?? '';
        $results = $_POST['results'] ?? '';

        Database::query(
            "UPDATE eafd_portfolio SET title = :t, slug = :s, client_name = :c, service_type = :st, technologies = :tech, summary = :sum, challenge = :chal, solution = :sol, results = :res WHERE id = :id",
            ['t' => $title, 's' => $slug, 'c' => $clientName, 'st' => $serviceType, 'tech' => $technologies, 'sum' => $summary, 'chal' => $challenge, 'sol' => $solution, 'res' => $results, 'id' => $id]
        );

        $this->redirect('/admin/portfolio');
    }

    public function delete(int $id): void {
        $this->checkAuth();
        Database::query("DELETE FROM eafd_portfolio WHERE id = :id", ['id' => $id]);
        $this->redirect('/admin/portfolio');
    }
}
