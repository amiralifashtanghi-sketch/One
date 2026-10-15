<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Database;

class AccountController extends Controller
{
    public function index(Request $request): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $ordersCount = Database::fetch("SELECT COUNT(*) as cnt FROM orders WHERE user_id = ?", [$userId])['cnt'] ?? 0;
        $licensesCount = Database::fetch("SELECT COUNT(*) as cnt FROM licenses WHERE user_id = ?", [$userId])['cnt'] ?? 0;

        $this->render('account.index', [
            'ordersCount' => $ordersCount,
            'licensesCount' => $licensesCount,
        ], 'main');
    }

    public function orders(Request $request): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $orders = Database::fetchAll("SELECT * FROM orders WHERE user_id = ? ORDER BY id DESC", [$userId]);

        $this->render('account.orders', ['orders' => $orders], 'main');
    }

    public function licenses(Request $request): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $licenses = Database::fetchAll("
            SELECT l.*, p.title as product_title, p.version as product_version
            FROM licenses l
            JOIN products p ON l.product_id = p.id
            WHERE l.user_id = ? ORDER BY l.id DESC
        ", [$userId]);

        foreach ($licenses as &$lic) {
            $lic['activations'] = Database::fetchAll("SELECT * FROM license_activations WHERE license_id = ?", [$lic['id']]);
        }

        $this->render('account.licenses', ['licenses' => $licenses], 'main');
    }
}
