<?php

namespace App\Controllers\Api;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Models\License;

class LicenseApiController extends Controller
{
    public function verify(Request $request): void
    {
        $licenseKey = $request->post('license_key') ?? $request->get('license_key');
        $domain = $request->post('domain') ?? $request->get('domain');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        if (empty($licenseKey) || empty($domain)) {
            Response::json(['valid' => false, 'message' => 'پارامترهای license_key و domain الزامی می‌باشند.'], 400);
        }

        $licenseModel = new License();
        $result = $licenseModel->verifyAndActivate((string)$licenseKey, (string)$domain, $ip);

        Response::json($result, $result['valid'] ? 200 : 403);
    }

    public function activate(Request $request): void
    {
        $licenseKey = $request->post('license_key');
        $domain = $request->post('domain');
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';

        if (empty($licenseKey) || empty($domain)) {
            Response::json(['success' => false, 'message' => 'اطلاعات لایسنس و دامنه ناکافی است.'], 400);
        }

        $licenseModel = new License();
        $result = $licenseModel->verifyAndActivate((string)$licenseKey, (string)$domain, $ip);

        Response::json([
            'success' => $result['valid'],
            'message' => $result['message'],
            'data' => $result,
        ]);
    }

    public function deactivate(Request $request): void
    {
        $licenseKey = $request->post('license_key');
        $domain = $request->post('domain');

        $license = Database::fetch("SELECT * FROM licenses WHERE license_key = ? LIMIT 1", [$licenseKey]);

        if (!$license) {
            Response::json(['success' => false, 'message' => 'لایسنس غیرمعتبر است.'], 404);
        }

        Database::query("DELETE FROM license_activations WHERE license_id = ? AND domain = ?", [$license['id'], $domain]);

        Response::json(['success' => true, 'message' => "دامنه {$domain} با موفقیت غیرفعال گردید."]);
    }

    public function check(Request $request): void
    {
        $licenseKey = $request->get('license_key');
        $license = Database::fetch("SELECT l.*, p.title as product_title, p.version as current_version FROM licenses l JOIN products p ON l.product_id = p.id WHERE l.license_key = ? LIMIT 1", [$licenseKey]);

        if (!$license) {
            Response::json(['status' => 'invalid', 'message' => 'کد لایسنس پیدا نشد.'], 404);
        }

        Response::json([
            'status' => $license['status'],
            'license_key' => $license['license_key'],
            'product_title' => $license['product_title'],
            'max_domains' => $license['max_domains'],
            'expires_at' => $license['expires_at'],
        ]);
    }

    public function update(Request $request): void
    {
        $licenseKey = $request->get('license_key') ?? $request->post('license_key');
        $currentVersion = $request->get('version') ?? $request->post('version');

        $license = Database::fetch("SELECT l.*, p.version as latest_version, p.title as product_title, p.file_path FROM licenses l JOIN products p ON l.product_id = p.id WHERE l.license_key = ? LIMIT 1", [$licenseKey]);

        if (!$license || $license['status'] !== 'active') {
            Response::json(['update_available' => false, 'message' => 'لایسنس معتبر نمی‌باشد.'], 403);
        }

        $updateAvailable = version_compare($license['latest_version'], (string)$currentVersion, '>');

        Response::json([
            'update_available' => $updateAvailable,
            'product_title' => $license['product_title'],
            'current_version' => $currentVersion,
            'latest_version' => $license['latest_version'],
            'download_url' => $updateAvailable ? "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/download?license=" . $license['license_key'] : '',
            'changelog' => 'بهینه‌سازی کارایی و به‌روزرسانی هسته امنیتی EAFD.',
        ]);
    }
}
