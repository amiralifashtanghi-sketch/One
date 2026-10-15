<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Database;

class LogController extends Controller
{
    public function index(Request $request): void
    {
        RBAC::checkPermission('view_logs');
        $logs = Database::fetchAll("SELECT * FROM activity_logs ORDER BY id DESC LIMIT 100");
        $this->render('admin.logs.index', ['logs' => $logs], 'admin');
    }
}
