<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Database;

class LicenseApiController extends Controller
{
    public function activate(Request $request): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $licenseKey = trim($request->post('license_key', ''));
        $productId = (int)$request->post('product_id', 0);
        $domain = strtolower(trim($request->post('domain', '')));

        if (empty($licenseKey) || empty($domain)) {
            echo json_encode(['success' => false, 'message' => 'کلید لایسنس و دامنه اجباری می‌باشند.']);
            exit;
        }

        Database::beginTransaction();
        try {
            $license = Database::fetch("SELECT * FROM licenses WHERE license_key = ? FOR UPDATE", [$licenseKey]);

            if (!$license) {
                Database::rollBack();
                echo json_encode(['success' => false, 'message' => 'کلید لایسنس وارد شده در سامانه وجود ندارد.']);
                exit;
            }

            if ($productId > 0 && (int)$license['product_id'] !== $productId) {
                Database::rollBack();
                echo json_encode(['success' => false, 'message' => 'این لایسنس متعلق به محصول دیگری می‌باشد.']);
                exit;
            }

            if ($license['status'] !== 'active') {
                Database::rollBack();
                echo json_encode(['success' => false, 'message' => 'این لایسنس در حال حاضر غیرفعال یا لغو گردیده است.']);
                exit;
            }

            // Check Expiration for Periodic Licenses
            if ($license['license_type'] === 'periodic' && !empty($license['expires_at'])) {
                if (strtotime($license['expires_at']) < time()) {
                    Database::rollBack();
                    echo json_encode(['success' => false, 'message' => 'اعتبار زمانی این لایسنس به پایان رسیده است.']);
                    exit;
                }
            }

            // Check Existing Activation for this domain
            $existing = Database::fetch("SELECT * FROM license_activations WHERE license_id = ? AND domain = ?", [$license['id'], $domain]);
            if ($existing) {
                Database::query("UPDATE license_activations SET last_check_at = CURRENT_TIMESTAMP WHERE id = ?", [$existing['id']]);
                Database::commit();
                echo json_encode([
                    'success' => true,
                    'message' => 'دامنه قبلاً فعال شده است.',
                    'grace_period_days' => 7,
                ]);
                exit;
            }

            // Check Max Activations Limit with locked count
            $activeCount = Database::fetch("SELECT COUNT(*) as cnt FROM license_activations WHERE license_id = ?", [$license['id']])['cnt'] ?? 0;
            $maxAllowed = (int)($license['max_activations'] ?? 1);

            if ($maxAllowed > 0 && $activeCount >= $maxAllowed) {
                Database::rollBack();
                echo json_encode(['success' => false, 'message' => "سقف تعداد فعال‌سازی این لایسنس ({$maxAllowed} دامنه) تکمیل شده است."]);
                exit;
            }

            // Create Activation
            Database::query("INSERT INTO license_activations (license_id, domain, activated_at, last_check_at) VALUES (?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)", [$license['id'], $domain]);
            Database::commit();

            echo json_encode([
                'success' => true,
                'message' => 'فعال‌سازی دامنه با موفقیت انجام شد.',
                'grace_period_days' => 7,
            ]);
            exit;
        } catch (\Exception $e) {
            Database::rollBack();
            echo json_encode(['success' => false, 'message' => 'خطا در انجام فعال‌سازی لایسنس.']);
            exit;
        }
    }

    public function deactivate(Request $request): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $licenseKey = trim($request->post('license_key', ''));
        $domain = strtolower(trim($request->post('domain', '')));

        $license = Database::fetch("SELECT * FROM licenses WHERE license_key = ?", [$licenseKey]);
        if ($license) {
            Database::query("DELETE FROM license_activations WHERE license_id = ? AND domain = ?", [$license['id'], $domain]);
        }

        echo json_encode(['success' => true, 'message' => 'دامنه با موفقیت غیرفعال شد.']);
        exit;
    }

    public function verify(Request $request): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $licenseKey = trim($request->all()['license_key'] ?? '');
        $domain = strtolower(trim($request->all()['domain'] ?? ''));

        $license = Database::fetch("SELECT * FROM licenses WHERE license_key = ?", [$licenseKey]);
        if (!$license || $license['status'] !== 'active') {
            echo json_encode(['valid' => false, 'message' => 'لایسنس غیرفعال یا نامعتبر است.']);
            exit;
        }

        $activation = Database::fetch("SELECT * FROM license_activations WHERE license_id = ? AND domain = ?", [$license['id'], $domain]);
        if (!$activation) {
            echo json_encode(['valid' => false, 'message' => 'این دامنه روی لایسنس فعال نشده است.']);
            exit;
        }

        echo json_encode(['valid' => true, 'grace_period_days' => 7]);
        exit;
    }

    public function update(Request $request): void
    {
        header('Content-Type: application/json; charset=utf-8');

        $productId = (int)($request->get('product_id') ?? $request->post('product_id', 0));
        $licenseKey = trim($request->get('license_key') ?? $request->post('license_key', ''));
        $currentVersion = trim($request->get('current_version') ?? $request->post('current_version', '1.0.0'));

        $license = Database::fetch("SELECT * FROM licenses WHERE license_key = ? AND product_id = ?", [$licenseKey, $productId]);

        if (!$license || $license['status'] !== 'active') {
            echo json_encode(['has_update' => false, 'message' => 'لایسنس معتبر نمی‌باشد.']);
            exit;
        }

        $product = Database::fetch("SELECT * FROM products WHERE id = ?", [$productId]);
        if (!$product) {
            echo json_encode(['has_update' => false, 'message' => 'محصول یافت نشد.']);
            exit;
        }

        $latestVersion = $product['version'] ?? '1.0.0';

        if (version_compare($latestVersion, $currentVersion, '>')) {
            $baseUrl = \App\Core\Config::get('config.url', 'https://eafd.ir');
            $downloadUrl = $baseUrl . '/download?product_id=' . $productId . '&license_key=' . $licenseKey;

            echo json_encode([
                'has_update' => true,
                'new_version' => $latestVersion,
                'download_url' => $downloadUrl,
                'changelog' => $product['changelog'] ?? 'به‌روزرسانی و بهبود کارایی.',
                'requires_wp' => $product['wp_version'] ?? '6.0',
                'requires_php' => $product['php_version'] ?? '8.2',
            ]);
            exit;
        }

        echo json_encode(['has_update' => false, 'message' => 'شما از آخرین نسخه استفاده می‌کنید.']);
        exit;
    }
}
