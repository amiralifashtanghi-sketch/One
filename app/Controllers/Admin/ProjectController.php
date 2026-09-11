<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Session;
use App\Core\Sanitizer;
use App\Core\Logger;
use App\Models\Project;

class ProjectController extends Controller
{
    protected Project $projectModel;

    public function __construct()
    {
        parent::__construct();
        RBAC::checkPermission('manage_projects');
        $this->projectModel = new Project();
    }

    public function index(Request $request): void
    {
        $projects = $this->projectModel->all();
        $this->render('admin.projects.index', ['projects' => $projects], 'admin');
    }

    public function create(Request $request): void
    {
        $this->render('admin.projects.edit', ['project' => null], 'admin');
    }

    public function edit(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $project = $this->projectModel->find($id);
        $this->render('admin.projects.edit', ['project' => $project], 'admin');
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
            'client_name' => $request->post('client_name'),
            'technologies' => $request->post('technologies'),
            'challenge' => $request->post('challenge'),
            'solution' => $request->post('solution'),
            'results' => $request->post('results'),
            'is_active' => (int)$request->post('is_active', 1),
        ];

        if ($id > 0) {
            $this->projectModel->update($id, $data);
            Logger::info("پروژه شناسه {$id} به‌روزرسانی شد.");
            Session::flash('success', 'اطلاعات پروژه با موفقیت ویرایش شد.');
        } else {
            $newId = $this->projectModel->create($data);
            Logger::info("پروژه جدید شناسه {$newId} اضافه شد.");
            Session::flash('success', 'پروژه جدید با موفقیت ایجاد گردید.');
        }

        $this->redirect('/admin/projects');
    }

    public function delete(Request $request, array $params): void
    {
        $id = (int)$params['id'];
        $this->projectModel->delete($id);
        Logger::info("پروژه شناسه {$id} حذف گردید.");
        Session::flash('success', 'پروژه مورد نظر حذف شد.');
        $this->redirect('/admin/projects');
    }
}
