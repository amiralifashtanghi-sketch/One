<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class ServiceController extends Controller {
    public function index(): void {
        try {
            $services = Database::fetchAll("SELECT * FROM eafd_services WHERE is_active = 1 ORDER BY sort_order ASC");
        } catch (\Exception $e) {
            $services = [];
        }

        $this->render('pages/services_index', [
            'pageTitle' => 'خدمات اختصاصی EAFD — مهندسی وب و سئو',
            'metaDescription' => 'لیست ۶ خدمت اصلی EAFD شامل طراحی سایت اختصاصی، سئو، توسعه قالب وردپرس و فروش آنلاین بدون وابستگی‌های سنگین.',
            'services' => $services
        ]);
    }

    public function show(string $slug): void {
        try {
            $service = Database::fetch("SELECT * FROM eafd_services WHERE slug = :slug AND is_active = 1 LIMIT 1", ['slug' => $slug]);
        } catch (\Exception $e) {
            $service = null;
        }

        if (!$service) {
            http_response_code(404);
            $this->render('pages/404', ['pageTitle' => 'خدمت یافت نشد']);
            return;
        }

        $this->render('pages/service_single', [
            'pageTitle' => $service['title'] . ' — EAFD',
            'metaDescription' => $service['short_description'],
            'service' => $service
        ]);
    }
}
