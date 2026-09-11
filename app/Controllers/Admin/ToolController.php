<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Core\Logger;
use App\Models\Tool;

class ToolController extends Controller
{
    protected Tool $toolModel;

    public function __construct()
    {
        parent::__construct();
        RBAC::checkPermission('manage_tools');
        $this->toolModel = new Tool();
    }

    public function index(Request $request): void
    {
        $tools = $this->toolModel->all();
        $this->render('admin.tools.index', ['tools' => $tools], 'admin');
    }

    public function create(Request $request): void
    {
        $this->render('admin.tools.edit', ['tool' => null], 'admin');
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $tool = $this->toolModel->find($id);
        $this->render('admin.tools.edit', ['tool' => $tool], 'admin');
    }

    public function save(Request $request, array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        $title = $request->post('title');
        $slug = $request->post('slug') ?: Sanitizer::sanitizeSlug($title);

        $data = [
            'title' => $title,
            'slug' => $slug,
            'description' => $request->post('description'),
            'tool_type' => $request->post('tool_type', 'calculator'),
            'config' => $request->post('config'),
            'is_active' => (int)$request->post('is_active', 1),
        ];

        if ($id > 0) {
            $this->toolModel->update($id, $data);
            Logger::info("ابزار آنلاین شناسه {$id} به‌روزرسانی گردید.");
            Session::flash('success', 'ابزار با موفقیت به‌روزرسانی شد.');
        } else {
            $newId = $this->toolModel->create($data);
            Logger::info("ابزار آنلاین جدید شناسه {$newId} اضافه شد.");
            Session::flash('success', 'ابزار جدید ایجاد شد.');
        }

        $this->redirect('/admin/tools');
    }

    public function delete(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $this->toolModel->delete($id);
        Logger::info("ابزار آنلاین شناسه {$id} حذف شد.");
        Session::flash('success', 'ابزار مورد نظر حذف شد.');
        $this->redirect('/admin/tools');
    }
}
