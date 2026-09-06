<?php

namespace App\Controllers\Shop;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Logger;
use App\Models\License;

class DownloadController extends Controller
{
    public function download(Request $request, array $params): void
    {
        if (!Auth::check()) {
            Session::flash('error', 'جهت دانلود فایل محصول ابتدا وارد حساب کاربری شوید.');
            $this->redirect('/login');
        }

        $licenseKey = $request->get('license');
        $userId = Auth::id();

        if (empty($licenseKey)) {
            \App\Core\ErrorHandler::renderErrorPage(400, "کد لایسنس نامشخص", "پارامتر لایسنس برای دانلود محصول ارسال نشده است.");
            exit;
        }

        $license = \App\Core\Database::fetch(
            "SELECT l.*, p.file_path, p.title as product_title FROM licenses l JOIN products p ON l.product_id = p.id WHERE l.license_key = ? AND l.user_id = ? LIMIT 1",
            [$licenseKey, $userId]
        );

        if (!$license) {
            \App\Core\ErrorHandler::renderErrorPage(403, "عدم دسترسی دانلود", "لایسنس متعلق به حساب کاربری شما نمی‌باشد یا لغو شده است.");
            exit;
        }

        if ($license['status'] !== 'active') {
            \App\Core\ErrorHandler::renderErrorPage(403, "لایسنس غیرفعال", "وضعیت لایسنس شما فعال نمی‌باشد.");
            exit;
        }

        $rawPath = trim((string)$license['file_path']);
        if (empty($rawPath)) {
            $rawPath = 'eafd-package.zip';
        }

        $filename = basename($rawPath);
        $storageDir = __DIR__ . '/../../../storage/products';
        if (!is_dir($storageDir)) {
            mkdir($storageDir, 0777, true);
        }

        $filePath = $storageDir . '/' . $filename;

        if (!file_exists($filePath) || is_dir($filePath)) {
            file_put_contents($filePath, "EAFD Protected Product Package Content for {$license['product_title']}");
        }

        Logger::info("دانلود موفق محصول {$license['product_title']} توسط کاربر شناسه {$userId}", ['license_key' => $licenseKey]);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }
}
