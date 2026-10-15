<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class HomeController extends Controller {
    public function index(): void {
        try {
            $services = Database::fetchAll("SELECT * FROM eafd_services WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 6");
            $faqs = Database::fetchAll("SELECT * FROM eafd_faqs WHERE is_active = 1 ORDER BY sort_order ASC LIMIT 5");
            $portfolio = Database::fetchAll("SELECT * FROM eafd_portfolio ORDER BY id DESC LIMIT 3");
        } catch (\Exception $e) {
            $services = [];
            $faqs = [];
            $portfolio = [];
        }

        $this->render('pages/home', [
            'pageTitle' => 'EAFD — سیستم اختصاصی مهندسی دیجیتال، طراحی وب و رشد',
            'metaDescription' => 'وب‌سایت رسمی EAFD؛ ارائه دهنده سیستم‌های اختصاصی وب، سئو، توسعه قالب وردپرس و دیجیتال مارکتینگ بدون تصویر و با سرعت ۱۰۰/۱۰۰.',
            'services' => $services,
            'faqs' => $faqs,
            'portfolio' => $portfolio
        ]);
    }
}
