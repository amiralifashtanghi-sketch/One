<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use App\Core\LicenseGenerator;

class License extends Model
{
    protected string $table = 'licenses';

    public function generateForOrder(int $userId, int $productId, int $orderId): array
    {
        $productModel = new Product();
        $product = $productModel->find($productId);
        $maxDomains = $product['max_domains'] ?? 1;

        $key = LicenseGenerator::generateKey();

        Database::query(
            "INSERT INTO licenses (user_id, product_id, order_id, license_key, max_domains, status, created_at) VALUES (?, ?, ?, ?, ?, 'active', ?)",
            [$userId, $productId, $orderId, $key, $maxDomains, date('Y-m-d H:i:s')]
        );

        $licenseId = (int)Database::lastInsertId();

        return $this->find($licenseId);
    }

    public function getLicensesWithDetails(int $userId = 0): array
    {
        $sql = "SELECT l.*, p.title as product_title, p.version as product_version, u.name as user_name, u.email as user_email
                FROM licenses l
                JOIN products p ON l.product_id = p.id
                JOIN users u ON l.user_id = u.id";

        if ($userId > 0) {
            $sql .= " WHERE l.user_id = " . (int)$userId;
        }

        $sql .= " ORDER BY l.id DESC";

        return Database::fetchAll($sql);
    }

    public function verifyAndActivate(string $licenseKey, string $domain, string $ipAddress = ''): array
    {
        $license = Database::fetch("SELECT * FROM licenses WHERE license_key = ? LIMIT 1", [$licenseKey]);

        if (!$license) {
            return ['valid' => false, 'message' => 'کد لایسنس غیرمعتبر است.'];
        }

        if ($license['status'] !== 'active') {
            return ['valid' => false, 'message' => 'لایسنس غیرفعال یا باطل شده است.'];
        }

        if ($license['expires_at'] !== null && strtotime($license['expires_at']) < time()) {
            Database::query("UPDATE licenses SET status = 'expired' WHERE id = ?", [$license['id']]);
            return ['valid' => false, 'message' => 'تاریخ اعتبار لایسنس به پایان رسیده است.'];
        }

        // Check registered activations
        $activations = Database::fetchAll("SELECT * FROM license_activations WHERE license_id = ?", [$license['id']]);
        $domainFound = false;

        foreach ($activations as $act) {
            if ($act['domain'] === $domain) {
                $domainFound = true;
                break;
            }
        }

        if (!$domainFound) {
            if (count($activations) >= (int)$license['max_domains']) {
                return ['valid' => false, 'message' => "حداکثر تعداد دامنه‌های مجاز ({$license['max_domains']}) تکمیل شده است."];
            }

            Database::query(
                "INSERT INTO license_activations (license_id, domain, ip_address, activated_at) VALUES (?, ?, ?, ?)",
                [$license['id'], $domain, $ipAddress, date('Y-m-d H:i:s')]
            );
        }

        return [
            'valid' => true,
            'message' => 'لایسنس معتبر است.',
            'license_key' => $license['license_key'],
            'max_domains' => $license['max_domains'],
            'active_domains' => count($activations) + ($domainFound ? 0 : 1),
        ];
    }
}
