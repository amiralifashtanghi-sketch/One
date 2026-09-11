<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    protected Project $projectModel;

    public function __construct()
    {
        parent::__construct();
        $this->projectModel = new Project();
    }

    public function index(Request $request): void
    {
        $projects = $this->projectModel->where('is_active', 1);
        $this->render('projects.index', ['projects' => $projects], 'main');
    }

    public function detail(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $project = $this->projectModel->findBy('slug', $slug);

        if (!$project || !$project['is_active']) {
            \App\Core\ErrorHandler::renderErrorPage(404, "پروژه یافت نشد", "پروژه مورد نظر وجود ندارد.");
            exit;
        }

        $this->render('projects.detail', ['project' => $project], 'main');
    }
}
