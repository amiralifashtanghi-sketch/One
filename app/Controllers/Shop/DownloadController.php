<?php

namespace App\Controllers\Shop;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Database;
use App\Models\Product;

class DownloadController extends Controller
{
    public function download(Request $request): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $productId = (int)$request->get('product_id', 0);
        $licenseKey = trim($request->get('license_key', ''));

        if (!$userId && empty($licenseKey)) {
            header('Location: /login');
            exit;
        }

        // Verify customer entitlement via active license key OR session user ID
        if (!empty($licenseKey)) {
            $hasLicense = Database::fetch("
                SELECT * FROM licenses
                WHERE license_key = ? AND product_id = ? AND status = 'active'
                LIMIT 1
            ", [$licenseKey, $productId]);
        } else {
            $hasLicense = Database::fetch("
                SELECT * FROM licenses
                WHERE user_id = ? AND product_id = ? AND status = 'active'
                LIMIT 1
            ", [$userId, $productId]);
        }

        if (!$hasLicense) {
            \App\Core\ErrorHandler::renderErrorPage(403, "عدم دسترسی", "شما به این فایل یا لایسنس خریدار شده دسترسی ندارید.");
            exit;
        }

        $productModel = new Product();
        $product = $productModel->find($productId);
        if (!$product) {
            \App\Core\ErrorHandler::renderErrorPage(404, "محصول یافت نشد", "فایل محصول مورد نظر موجود نمی‌باشد.");
            exit;
        }

        $version = $product['version'] ?? '1.0.0';
        $protectedZip = EAFD_BASE_DIR . '/storage/private/products/' . $productId . '/v_' . $version . '/protected.zip';

        if (!file_exists($protectedZip)) {
            // Fallback to product protected_file_path or original file if versioned protected ZIP doesn't exist yet
            $protectedZip = $product['protected_file_path'] ?? $product['file_path'] ?? '';
            if (!file_exists($protectedZip)) {
                \App\Core\ErrorHandler::renderErrorPage(404, "فایل دانلودی موجود نیست", "نسخه نهایی محافظت شده‌ی محصول برای دانلود یافت نشد.");
                exit;
            }
        }

        // Deliver File Securely
        $fileName = 'eafd-' . ($product['slug'] ?? 'product') . '-v' . $version . '.zip';
        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . filesize($protectedZip));
        header('Pragma: public');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        readfile($protectedZip);
        exit;
    }
}
