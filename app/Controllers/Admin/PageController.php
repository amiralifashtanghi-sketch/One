<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Models\Page;

class PageController extends Controller
{
    protected Page $pageModel;

    public function __construct()
    {
        parent::__construct();
        RBAC::checkPermission('manage_pages');
        $this->pageModel = new Page();
    }

    public function index(Request $request): void
    {
        $pages = $this->pageModel->all();
        $this->render('admin.pages.index', ['pages' => $pages], 'admin');
    }

    public function create(Request $request): void
    {
        $this->render('admin.pages.edit', ['page' => null], 'admin');
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $page = $this->pageModel->find($id);
        $this->render('admin.pages.edit', ['page' => $page], 'admin');
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
            'is_published' => (int)$request->post('is_published', 1),
        ];

        if ($id > 0) {
            $this->pageModel->update($id, $data);
            Session::flash('success', 'اطلاعات برگه به‌روزرسانی شد.');
        } else {
            $newId = $this->pageModel->create($data);
            Session::flash('success', 'برگه جدید با موفقیت ایجاد گردید.');
        }

        $this->redirect('/admin/pages');
    }

    public function delete(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $this->pageModel->delete($id);
        Session::flash('success', 'برگه حذف گردید.');
        $this->redirect('/admin/pages');
    }
}
