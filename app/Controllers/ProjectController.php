<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Security;
use App\Core\Database;

class ProjectController extends Controller {
    public function index(): void {
        $this->render('pages/start_project', [
            'pageTitle' => 'فرم پیکربندی و شروع پروژه اختصاصی EAFD',
            'metaDescription' => 'پیکربندی هوشمند نیازهای پروژه، انتخاب خدمات، برآورد بودجه و ارسال مستقیم درخواست به پنل مدیریت EAFD.',
            'csrfToken' => Security::generateCSRFToken()
        ]);
    }

    public function submit(): void {
        if (!Security::verifyCSRFToken($_POST['csrf_token'] ?? '')) {
            $this->json(['error' => 'نشست امنیتی معتبر نیست.'], 400);
        }

        if (!Security::checkRateLimit('start_project', 3, 300)) {
            $this->json(['error' => 'تعداد درخواست‌های شما بیش از حد مجاز است. لطفاً چند دقیقه بعد تلاش کنید.'], 429);
        }

        $name = Security::sanitizeString($_POST['name'] ?? '');
        $phone = Security::sanitizeString($_POST['phone'] ?? '');
        $email = Security::sanitizeEmail($_POST['email'] ?? '');
        $projectType = Security::sanitizeString($_POST['project_type'] ?? '');
        $budget = Security::sanitizeString($_POST['budget'] ?? '');
        $message = Security::sanitizeString($_POST['message'] ?? '');

        if (empty($name) || empty($phone) || empty($projectType) || empty($message)) {
            $this->json(['error' => 'لطفاً نام، شماره تماس، نوع پروژه و توضیحات را تکمیل فرمایید.'], 400);
        }

        try {
            Database::query(
                "INSERT INTO eafd_leads (name, phone, email, project_type, budget, message) VALUES (:n, :p, :e, :pt, :b, :m)",
                ['n' => $name, 'p' => $phone, 'e' => $email, 'pt' => $projectType, 'b' => $budget, 'm' => $message]
            );

            $this->json(['success' => true, 'message' => 'درخواست پروژه شما با موفقیت ثبت شد. کارشناسان EAFD به‌زودی با شما تماس خواهند گرفت.']);
        } catch (\Exception $e) {
            $this->json(['error' => 'خطا در ثبت درخواست در پایگاه داده.'], 500);
        }
    }
}
