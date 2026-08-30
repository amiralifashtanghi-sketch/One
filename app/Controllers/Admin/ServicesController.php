<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security;
use App\Core\SessionManager;
use App\Core\Database;

class ServicesController extends Controller {
    private function checkAuth(): void {
        SessionManager::start();
        if (!SessionManager::get('admin_user')) {
            $this->redirect('/admin/login');
        }
    }

    public function index(): void {
        $this->checkAuth();
        try {
            $services = Database::fetchAll("SELECT * FROM eafd_services ORDER BY sort_order ASC, id DESC");
        } catch (\Exception $e) {
            $services = [];
        }
        $this->render('admin/services/index', [
            'pageTitle' => 'مدیریت خدمات EAFD',
            'services' => $services
        ], 'admin');
    }

    public function create(): void {
        $this->checkAuth();
        $this->render('admin/services/form', [
            'pageTitle' => 'تعریف خدمت جدید',
            'service' => null,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function store(): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/services');
        }

        $title = Security::sanitizeString($_POST['title'] ?? '');
        $slug = Security::sanitizeString($_POST['slug'] ?? '');
        $shortDesc = Security::sanitizeString($_POST['short_description'] ?? '');
        $fullDesc = $_POST['full_description'] ?? '';
        $iconSvg = $_POST['icon_svg'] ?? '';
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        if (!empty($title) && !empty($slug)) {
            Database::query(
                "INSERT INTO eafd_services (title, slug, short_description, full_description, icon_svg, sort_order, is_active) VALUES (:t, :s, :sd, :fd, :icon, :so, :act)",
                ['t' => $title, 's' => $slug, 'sd' => $shortDesc, 'fd' => $fullDesc, 'icon' => $iconSvg, 'so' => $sortOrder, 'act' => $isActive]
            );
        }

        $this->redirect('/admin/services');
    }

    public function edit(int $id): void {
        $this->checkAuth();
        $service = Database::fetch("SELECT * FROM eafd_services WHERE id = :id LIMIT 1", ['id' => $id]);
        if (!$service) {
            $this->redirect('/admin/services');
        }

        $this->render('admin/services/form', [
            'pageTitle' => 'ویرایش خدمت',
            'service' => $service,
            'csrfToken' => Security::generateCSRFToken()
        ], 'admin');
    }

    public function update(int $id): void {
        $this->checkAuth();
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->redirect('/admin/services');
        }

        $title = Security::sanitizeString($_POST['title'] ?? '');
        $slug = Security::sanitizeString($_POST['slug'] ?? '');
        $shortDesc = Security::sanitizeString($_POST['short_description'] ?? '');
        $fullDesc = $_POST['full_description'] ?? '';
        $iconSvg = $_POST['icon_svg'] ?? '';
        $sortOrder = (int)($_POST['sort_order'] ?? 0);
        $isActive = isset($_POST['is_active']) ? 1 : 0;

        Database::query(
            "UPDATE eafd_services SET title = :t, slug = :s, short_description = :sd, full_description = :fd, icon_svg = :icon, sort_order = :so, is_active = :act WHERE id = :id",
            ['t' => $title, 's' => $slug, 'sd' => $shortDesc, 'fd' => $fullDesc, 'icon' => $iconSvg, 'so' => $sortOrder, 'act' => $isActive, 'id' => $id]
        );

        $this->redirect('/admin/services');
    }

    public function delete(int $id): void {
        $this->checkAuth();
        Database::query("DELETE FROM eafd_services WHERE id = :id", ['id' => $id]);
        $this->redirect('/admin/services');
    }
}
