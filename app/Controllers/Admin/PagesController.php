<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security;
use App\Core\SessionManager;
use App\Core\Database;

class PagesController extends Controller {
    private function checkAuth(): void {
        SessionManager::start();
        if (!SessionManager::get('admin_user')) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void {
        $this->checkAuth();
        try {
            $pages = Database::fetchAll("SELECT * FROM eafd_pages ORDER BY id DESC");
        } catch (\Exception $e) {
            $pages = [];
        }
        $this->render('admin/pages/index', [
            'pageTitle' => 'مدیریت صفحات',
            'pages' => $pages
        ], 'admin');
    }

    public function create(): void {
        $this->checkAuth();
        $this->render('admin/pages/form', [
            'pageTitle' => 'ایجاد صفحه جدید',
            'page' => null,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function store(): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/pages');
        }

        $title = Security::sanitizeString($_POST['title'] ?? '');
        $slug = Security::sanitizeString($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $metaTitle = Security::sanitizeString($_POST['meta_title'] ?? '');
        $metaDesc = Security::sanitizeString($_POST['meta_description'] ?? '');
        $status = $_POST['status'] ?? 'published';

        if (!empty($title) && !empty($slug)) {
            Database::query(
                "INSERT INTO eafd_pages (title, slug, content, meta_title, meta_description, status) VALUES (:t, :s, :c, :mt, :md, :st)",
                ['t' => $title, 's' => $slug, 'c' => $content, 'mt' => $metaTitle, 'md' => $metaDesc, 'st' => $status]
            );
        }

        $this->redirect('/admin/pages');
    }

    public function edit(int $id): void {
        $this->checkAuth();
        $page = Database::fetch("SELECT * FROM eafd_pages WHERE id = :id LIMIT 1", ['id' => $id]);
        if (!$page) {
            $this->redirect('/admin/pages');
        }

        $this->render('admin/pages/form', [
            'pageTitle' => 'ویرایش صفحه',
            'page' => $page,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function update(int $id): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/pages');
        }

        $title = Security::sanitizeString($_POST['title'] ?? '');
        $slug = Security::sanitizeString($_POST['slug'] ?? '');
        $content = $_POST['content'] ?? '';
        $metaTitle = Security::sanitizeString($_POST['meta_title'] ?? '');
        $metaDesc = Security::sanitizeString($_POST['meta_description'] ?? '');
        $status = $_POST['status'] ?? 'published';

        Database::query(
            "UPDATE eafd_pages SET title = :t, slug = :s, content = :c, meta_title = :mt, meta_description = :md, status = :st WHERE id = :id",
            ['t' => $title, 's' => $slug, 'c' => $content, 'mt' => $metaTitle, 'md' => $metaDesc, 'st' => $status, 'id' => $id]
        );

        $this->redirect('/admin/pages');
    }

    public function delete(int $id): void {
        $this->checkAuth();
        Database::query("DELETE FROM eafd_pages WHERE id = :id", ['id' => $id]);
        $this->redirect('/admin/pages');
    }
}
