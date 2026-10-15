<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class LabController extends Controller {
    public function index(): void {
        try {
            $labs = Database::fetchAll("SELECT * FROM eafd_lab ORDER BY id DESC");
        } catch (\Exception $e) {
            $labs = [];
        }

        $this->render('pages/lab_index', [
            'pageTitle' => 'آزمایشگاه EAFD (LAB) — کانسپت‌ها و پروژه‌های تجربی وب',
            'metaDescription' => 'پروژه‌های تحقیقاتی، ماژول‌های تجربی و دستاوردهای فنی EAFD در حوزه وب، لایت‌هاوس و ایجنت‌های هوشمند.',
            'labs' => $labs
        ]);
    }
}
