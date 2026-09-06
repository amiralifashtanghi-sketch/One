<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Database;
use App\Core\Session;
use App\Core\Auth;

class UserController extends Controller
{
    public function index(Request $request): void
    {
        RBAC::checkPermission('manage_users');
        $users = Database::fetchAll("SELECT * FROM users ORDER BY id DESC");
        $this->render('admin.users.index', ['users' => $users], 'admin');
    }

    public function edit(Request $request, array $params): void
    {
        RBAC::checkPermission('manage_users');
        $id = (int)$params['id'];
        $user = Database::fetch("SELECT * FROM users WHERE id = ?", [$id]);
        $this->render('admin.users.edit', ['user' => $user], 'admin');
    }

    public function save(Request $request, array $params): void
    {
        RBAC::checkPermission('manage_users');
        $id = (int)$params['id'];
        $role = $request->post('role', 'customer');
        $isActive = (int)$request->post('is_active', 1);

        Database::query("UPDATE users SET role = ?, is_active = ? WHERE id = ?", [$role, $isActive, $id]);
        Session::flash('success', 'نقش و وضعیت کاربر به‌روزرسانی شد.');
        $this->redirect('/admin/users');
    }
}
