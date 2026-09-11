<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Core\Logger;
use App\Models\Service;

class ServiceController extends Controller
{
    protected Service $serviceModel;

    public function __construct()
    {
        parent::__construct();
        RBAC::checkPermission('manage_services');
        $this->serviceModel = new Service();
    }

    public function index(Request $request): void
    {
        $services = $this->serviceModel->all();
        $this->render('admin.services.index', ['services' => $services], 'admin');
    }

    public function create(Request $request): void
    {
        $this->render('admin.services.edit', ['service' => null], 'admin');
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $service = $this->serviceModel->find($id);
        $this->render('admin.services.edit', ['service' => $service], 'admin');
    }

    public function save(Request $request, array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        $title = $request->post('title');
        $slug = $request->post('slug') ?: Sanitizer::sanitizeSlug($title);
        $summary = $request->post('summary');
        $icon = $request->post('icon', '⚡');
        $content = $request->post('content');
        $priceStart = $request->post('price_start');

        $data = [
            'title' => $title,
            'slug' => $slug,
            'summary' => $summary,
            'icon' => $icon,
            'content' => $content,
            'price_start' => $priceStart,
            'is_active' => (int)$request->post('is_active', 1),
        ];

        if ($id > 0) {
            $this->serviceModel->update($id, $data);
            Logger::info("خدمت شناسه {$id} به‌روزرسانی شد.", ['service_id' => $id]);
            Session::flash('success', 'اطلاعات خدمت با موفقیت به‌روزرسانی شد.');
        } else {
            $newId = $this->serviceModel->create($data);
            Logger::info("خدمت جدید شناسه {$newId} ثبت گردید.");
            Session::flash('success', 'خدمت جدید با موفقیت اضافه شد.');
        }

        $this->redirect('/admin/services');
    }

    public function delete(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $this->serviceModel->delete($id);
        Logger::info("خدمت شناسه {$id} حذف گردید.");
        Session::flash('success', 'خدمت مورد نظر حذف شد.');
        $this->redirect('/admin/services');
    }
}
