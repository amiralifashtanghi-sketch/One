<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class PortfolioController extends Controller {
    public function index(): void {
        try {
            $items = Database::fetchAll("SELECT * FROM eafd_portfolio ORDER BY id DESC");
        } catch (\Exception $e) {
            $items = [];
        }

        $this->render('pages/portfolio_index', [
            'pageTitle' => 'نمونه‌کارهای اختصاصی EAFD — اثبات توانایی با داده و نتایج',
            'metaDescription' => 'مشاهده پروژه‌های موفق EAFD، چالش‌های مهندسی، معماری انتخاب شده و نتایج ملموس در سرعت و سئو بدون وابستگی به عکس.',
            'items' => $items
        ]);
    }

    public function show(string $slug): void {
        try {
            $item = Database::fetch("SELECT * FROM eafd_portfolio WHERE slug = :slug LIMIT 1", ['slug' => $slug]);
        } catch (\Exception $e) {
            $item = null;
        }

        if (!$item) {
            http_response_code(404);
            $this->render('pages/404', ['pageTitle' => 'پروژه یافت نشد']);
            return;
        }

        $this->render('pages/portfolio_single', [
            'pageTitle' => $item['title'] . ' — مطالعات موردی EAFD',
            'metaDescription' => $item['summary'],
            'item' => $item
        ]);
    }
}
