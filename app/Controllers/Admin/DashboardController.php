<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Database;

class DashboardController extends Controller
{
    public function index(Request $request): void
    {
        RBAC::checkPermission('access_admin');

        $usersCount = Database::fetch("SELECT COUNT(*) as count FROM users")['count'] ?? 0;
        $ordersCount = Database::fetch("SELECT COUNT(*) as count FROM orders")['count'] ?? 0;
        $licensesCount = Database::fetch("SELECT COUNT(*) as count FROM licenses")['count'] ?? 0;
        $revenue = Database::fetch("SELECT SUM(total_amount) as total FROM orders WHERE status = 'completed'")['total'] ?? 0;

        $recentOrders = Database::fetchAll("SELECT o.*, u.name as user_name FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.id DESC LIMIT 5");

        $this->render('admin.dashboard.index', [
            'usersCount' => $usersCount,
            'ordersCount' => $ordersCount,
            'licensesCount' => $licensesCount,
            'revenue' => $revenue,
            'recentOrders' => $recentOrders,
        ], 'admin');
    }
}
