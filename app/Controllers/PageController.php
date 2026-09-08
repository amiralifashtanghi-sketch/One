<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Models\Page;

class PageController extends Controller
{
    protected Page $pageModel;

    public function __construct()
    {
        $this->pageModel = new Page();
    }

    public function show(Request $request, array $params): void
    {
        $slug = $params['slug'] ?? '';

        // Check static system pages first
        if ($slug === 'about' || $slug === 'درباره-ما') {
            $this->about();
            return;
        }
        if ($slug === 'contact' || $slug === 'تماس-با-ما') {
            $this->contact();
            return;
        }
        if ($slug === 'faq' || $slug === 'سوالات-متداول') {
            $this->faq();
            return;
        }

        $page = $this->pageModel->getBySlug($slug);

        if (!$page) {
            $this->render404("صفحه مورد نظر پیدا نشد", "صفحه‌ای با شناسه {$slug} در سیستم ثبت نشده است.");
            return;
        }

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $page['title'],
            'description' => substr(strip_tags($page['content'] ?? ''), 0, 160)
        ];

        $this->render('pages/show', [
            'meta_title' => $page['title'] . ' | EAFD',
            'meta_description' => substr(strip_tags($page['content'] ?? ''), 0, 160),
            'page' => $page,
            'schema' => $schema
        ]);
    }

    public function about(): void
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'AboutPage',
            'name' => 'درباره EAFD',
            'description' => 'آشنایی با پلتفرم اختصاصی، اهداف فنی، استانداردها و معماری EAFD'
        ];

        $this->render('pages/about', [
            'meta_title' => 'درباره ما | EAFD',
            'meta_description' => 'آشنایی با پلتفرم اختصاصی، اهداف فنی، استانداردها و معماری EAFD',
            'schema' => $schema
        ]);
    }

    public function contact(): void
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => 'تماس با EAFD',
            'description' => 'ارتباط با تیم فنی و پشتیبانی پلتفرم EAFD'
        ];

        $this->render('pages/contact', [
            'meta_title' => 'تماس با ما | EAFD',
            'meta_description' => 'ارتباط با تیم فنی و پشتیبانی پلتفرم EAFD',
            'schema' => $schema
        ]);
    }

    public function faq(): void
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'name' => 'سوالات متداول | EAFD',
            'description' => 'پاسخ به سوالات متداول کارفرمایان در خصوص خدمات، ابزارها و لایسنس‌های EAFD'
        ];

        $this->render('pages/faq', [
            'meta_title' => 'سوالات متداول | EAFD',
            'meta_description' => 'پاسخ به سوالات متداول کارفرمایان در خصوص خدمات، ابزارها و لایسنس‌های EAFD',
            'schema' => $schema
        ]);
    }
}
