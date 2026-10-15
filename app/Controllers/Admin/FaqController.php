<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security;
use App\Core\SessionManager;
use App\Core\Database;

class FaqController extends Controller {
    private function checkAuth(): void {
        SessionManager::start();
        if (!SessionManager::get('admin_user')) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void {
        $this->checkAuth();
        try {
            $faqs = Database::fetchAll("SELECT * FROM eafd_faqs ORDER BY sort_order ASC, id DESC");
        } catch (\Exception $e) {
            $faqs = [];
        }
        $this->render('admin/faq/index', [
            'pageTitle' => 'مدیریت سوالات متداول (FAQ)',
            'faqs' => $faqs,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function store(): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/faq');
        }

        $question = Security::sanitizeString($_POST['question'] ?? '');
        $answer = Security::sanitizeString($_POST['answer'] ?? '');
        $sortOrder = (int)($_POST['sort_order'] ?? 0);

        if (!empty($question) && !empty($answer)) {
            Database::query(
                "INSERT INTO eafd_faqs (question, answer, sort_order) VALUES (:q, :a, :so)",
                ['q' => $question, 'a' => $answer, 'so' => $sortOrder]
            );
        }

        $this->redirect('/admin/faq');
    }

    public function delete(int $id): void {
        $this->checkAuth();
        Database::query("DELETE FROM eafd_faqs WHERE id = :id", ['id' => $id]);
        $this->redirect('/admin/faq');
    }
}
