<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Session;
use App\Core\Logger;
use App\Models\License;

class LicenseController extends Controller
{
    protected License $licenseModel;

    public function __construct()
    {
        parent::__construct();
        RBAC::checkPermission('manage_licenses');
        $this->licenseModel = new License();
    }

    public function index(Request $request): void
    {
        $licenses = $this->licenseModel->getLicensesWithDetails();
        $this->render('admin.licenses.index', ['licenses' => $licenses], 'admin');
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $license = $this->licenseModel->find($id);
        $this->render('admin.licenses.edit', ['license' => $license], 'admin');
    }

    public function save(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $status = $request->post('status', 'active');
        $maxDomains = (int)$request->post('max_domains', 1);

        $this->licenseModel->update($id, [
            'status' => $status,
            'max_domains' => $maxDomains,
        ]);

        Logger::info("وضعیت لایسنس شناسه {$id} به {$status} تغییر یافت.");
        Session::flash('success', 'اطلاعات لایسنس به‌روزرسانی شد.');
        $this->redirect('/admin/licenses');
    }

    public function revoke(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $this->licenseModel->update($id, ['status' => 'revoked']);
        Logger::info("لایسنس شناسه {$id} باطل گردید.");
        Session::flash('success', 'لایسنس با موفقیت باطل شد.');
        $this->redirect('/admin/licenses');
    }
}
