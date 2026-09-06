<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Request;
use App\Core\RBAC;
use App\Core\Config;
use App\Core\Session;
use App\Core\Logger;
use App\Models\Page;
use App\Models\PageSection;

class PageBuilderController extends Controller
{
    protected Page $pageModel;
    protected PageSection $sectionModel;

    public function __construct()
    {
        parent::__construct();
        RBAC::checkPermission('manage_pages');
        $this->pageModel = new Page();
        $this->sectionModel = new PageSection();
    }

    public function edit(Request $request, array $params): void
    {
        $pageId = (int)$params['id'];
        $page = $this->pageModel->find($pageId);

        if (!$page) {
            \App\Core\ErrorHandler::renderErrorPage(404, "برگه یافت نشد", "برگه مورد نظر در سیستم وجود ندارد.");
            exit;
        }

        $sections = $this->sectionModel->getSectionsForPage($pageId);
        $schemas = Config::get('component-schemas', []);
        $revisions = $this->sectionModel->getRevisions($pageId);

        $this->render('admin.pages.builder', [
            'page' => $page,
            'sections' => $sections,
            'schemas' => $schemas,
            'revisions' => $revisions,
        ], 'admin');
    }

    public function save(Request $request, array $params): void
    {
        $pageId = (int)$params['id'];
        $sections = $request->post('sections', []);

        // Create revision backup before saving
        $existingSections = $this->sectionModel->getSectionsForPage($pageId);
        $this->sectionModel->createRevision($pageId, $existingSections);

        // Process sections
        foreach ($sections as $index => $sectionData) {
            $sectionId = (int)($sectionData['id'] ?? 0);
            $type = $sectionData['type'] ?? 'hero';
            $settings = json_encode($sectionData['settings'] ?? [], JSON_UNESCAPED_UNICODE);

            if ($sectionId > 0) {
                $this->sectionModel->update($sectionId, [
                    'section_type' => $type,
                    'settings' => $settings,
                    'sort_order' => $index + 1,
                ]);
            } else {
                $this->sectionModel->create([
                    'page_id' => $pageId,
                    'section_type' => $type,
                    'settings' => $settings,
                    'sort_order' => $index + 1,
                    'is_published' => 1,
                ]);
            }
        }

        Logger::info("چیدمان بخش‌های برگه شناسه {$pageId} به‌روزرسانی شد.", ['page_id' => $pageId]);
        Session::flash('success', 'تغییرات صفحه‌ساز با موفقیت ذخیره گردید.');
        $this->redirect("/admin/pages/{$pageId}/builder");
    }

    public function addSection(Request $request, array $params): void
    {
        $pageId = (int)$params['id'];
        $type = $request->post('section_type', 'hero');

        $this->sectionModel->create([
            'page_id' => $pageId,
            'section_type' => $type,
            'settings' => json_encode(['title' => 'بخش جدید'], JSON_UNESCAPED_UNICODE),
            'sort_order' => 99,
            'is_published' => 1,
        ]);

        Session::flash('success', 'بخش جدید اضافه گردید.');
        $this->redirect("/admin/pages/{$pageId}/builder");
    }

    public function deleteSection(Request $request, array $params): void
    {
        $pageId = (int)$params['page_id'];
        $sectionId = (int)$params['section_id'];

        $this->sectionModel->delete($sectionId);
        Session::flash('success', 'بخش مورد نظر حذف گردید.');
        $this->redirect("/admin/pages/{$pageId}/builder");
    }
}
