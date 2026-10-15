<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Core\Logger;
use App\Models\Product;

class ProductController extends Controller
{
    protected Product $productModel;

    public function __construct()
    {
        parent::__construct();
        RBAC::checkPermission('manage_products');
        $this->productModel = new Product();
    }

    public function index(Request $request): void
    {
        $products = $this->productModel->all();
        $this->render('admin.products.index', ['products' => $products], 'admin');
    }

    public function create(Request $request): void
    {
        $this->render('admin.products.edit', ['product' => null], 'admin');
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $product = $this->productModel->find($id);
        $this->render('admin.products.edit', ['product' => $product], 'admin');
    }

    public function save(Request $request, array $params = []): void
    {
        $id = (int)($params['id'] ?? 0);
        $title = $request->post('title');
        $slug = $request->post('slug') ?: Sanitizer::sanitizeSlug($title);

        $data = [
            'title' => $title,
            'slug' => $slug,
            'summary' => $request->post('summary'),
            'description' => $request->post('description'),
            'price' => (int)$request->post('price', 0),
            'type' => $request->post('type', 'plugin'),
            'version' => $request->post('version', '1.0.0'),
            'file_path' => $request->post('file_path'),
            'license_type' => $request->post('license_type', 'lifetime'),
            'max_domains' => (int)$request->post('max_domains', 1),
            'is_active' => (int)$request->post('is_active', 1),
        ];

        if ($id > 0) {
            $this->productModel->update($id, $data);
            Logger::info("محصول دیجیتال شناسه {$id} به‌روزرسانی شد.");
            Session::flash('success', 'اطلاعات محصول به‌روزرسانی شد.');
        } else {
            $newId = $this->productModel->create($data);
            Logger::info("محصول دیجیتال جدید شناسه {$newId} ثبت گردید.");
            Session::flash('success', 'محصول دیجیتال جدید اضافه شد.');
        }

        $this->redirect('/admin/products');
    }

    public function delete(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $this->productModel->delete($id);
        Logger::info("محصول دیجیتال شناسه {$id} حذف شد.");
        Session::flash('success', 'محصول حذف گردید.');
        $this->redirect('/admin/products');
    }
}
