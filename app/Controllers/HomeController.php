<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Page;
use App\Models\PageSection;

class HomeController extends Controller
{
    public function index(Request $request): void
    {
        $pageModel = new Page();
        $sectionModel = new PageSection();

        $page = $pageModel->getBySlug('home');
        $sections = $page ? $sectionModel->getSectionsForPage((int)$page['id']) : [];

        $this->render('home', [
            'page' => $page,
            'sections' => $sections,
        ], 'main');
    }
}
