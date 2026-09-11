<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Service;

class ServiceController extends Controller
{
    protected Service $serviceModel;

    public function __construct()
    {
        parent::__construct();
        $this->serviceModel = new Service();
    }

    public function index(Request $request): void
    {
        $services = $this->serviceModel->where('is_active', 1);
        $this->render('services.index', ['services' => $services], 'main');
    }

    public function detail(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';
        $service = $this->serviceModel->findBy('slug', $slug);

        if (!$service || !$service['is_active']) {
            \App\Core\ErrorHandler::renderErrorPage(404, "خدمت یافت نشد", "خدمت مورد نظر در سامانه موجود نمی‌باشد.");
            exit;
        }

        $this->render('services.detail', ['service' => $service], 'main');
    }
}
