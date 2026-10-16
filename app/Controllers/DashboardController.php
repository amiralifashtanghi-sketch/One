<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\Helper;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;

class DashboardController {
    private int $userId;
    private array $bio;

    public function __construct() {
        if (!Session::has('user_id')) {
            Session::flash('error', 'لطفاً ابتدا وارد حساب کاربری خود شوید.');
            Response::redirect('/login');
        }

        $this->userId = Session::get('user_id');

        // Ensure user has a bio page
        $bio = Database::fetch("SELECT * FROM bio_pages WHERE user_id = ?", [$this->userId]);
        if (!$bio) {
            $user = Database::fetch("SELECT phone FROM users WHERE id = ?", [$this->userId]);
            $username = 'u' . substr($user['phone'] ?? rand(1000, 9999), -4) . rand(100, 999);
            Database::query("INSERT INTO bio_pages (user_id, username, title) VALUES (?, ?, 'صفحه بیو من')", [$this->userId, $username]);
            $bio = Database::fetch("SELECT * FROM bio_pages WHERE user_id = ?", [$this->userId]);
        }

        $this->bio = $bio;
    }

    public function index(): void {
        $blocksCount = Database::fetch("SELECT COUNT(*) AS cnt FROM bio_blocks WHERE bio_page_id = ?", [$this->bio['id']])['cnt'] ?? 0;
        $leadsCount = Database::fetch("SELECT COUNT(*) AS cnt FROM leads WHERE bio_page_id = ?", [$this->bio['id']])['cnt'] ?? 0;
        $bookingsCount = Database::fetch("SELECT COUNT(*) AS cnt FROM bookings WHERE bio_page_id = ?", [$this->bio['id']])['cnt'] ?? 0;

        Response::render('dashboard.index', [
            'title' => 'پنل مدیریت بیو',
            'activePage' => 'dashboard',
            'bio' => $this->bio,
            'blocksCount' => $blocksCount,
            'leadsCount' => $leadsCount,
            'bookingsCount' => $bookingsCount,
            'appUrl' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function profileView(): void {
        Response::render('dashboard.profile', [
            'title' => 'ویرایش مشخصات عمومی بیو',
            'activePage' => 'profile',
            'bio' => $this->bio,
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function profileSaveProcess(): void {
        $title = trim(Request::post('title', ''));
        $description = trim(Request::post('description', ''));
        $username = Helper::slugify(Request::post('username', ''));

        if (empty($username)) {
            Session::flash('error', 'یوزرنیم نمی‌تواند خالی باشد.');
            Response::redirect('/dashboard/profile');
        }

        // Check if username is reserved
        if (\App\Core\Router::isReserved($username)) {
            Session::flash('error', 'این یوزرنیم رزرو شده است و قابل استفاده نیست.');
            Response::redirect('/dashboard/profile');
        }

        // Check username uniqueness
        $existing = Database::fetch("SELECT id FROM bio_pages WHERE username = ? AND id != ?", [$username, $this->bio['id']]);
        if ($existing) {
            Session::flash('error', 'این یوزرنیم قبلاً ثبت شده است.');
            Response::redirect('/dashboard/profile');
        }

        $avatarPath = $this->bio['avatar'];
        $file = Request::file('avatar');
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp'])) {
                $filename = 'avatar_' . $this->bio['id'] . '_' . time() . '.' . $ext;
                $target = __DIR__ . '/../../uploads/' . $filename;
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    $avatarPath = '/uploads/' . $filename;
                }
            }
        }

        Database::query("
            UPDATE bio_pages
            SET title = ?, description = ?, username = ?, avatar = ?
            WHERE id = ?
        ", [$title, $description, $username, $avatarPath, $this->bio['id']]);

        Session::flash('success', 'مشخصات پروفایل با موفقیت بروزرسانی شد.');
        Response::redirect('/dashboard/profile');
    }

    public function themeView(): void {
        Response::render('dashboard.theme', [
            'title' => 'سفارشی‌سازی ظاهر و تم',
            'activePage' => 'theme',
            'bio' => $this->bio,
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function themeSaveProcess(): void {
        $theme = Request::post('theme', 'default');
        Database::query("UPDATE bio_pages SET theme = ? WHERE id = ?", [$theme, $this->bio['id']]);
        Session::flash('success', 'تم رنگی با موفقیت بروزرسانی شد.');
        Response::redirect('/dashboard/theme');
    }

    public function blocksView(): void {
        $blocks = Database::fetchAll("SELECT * FROM bio_blocks WHERE bio_page_id = ? ORDER BY sort_order ASC, id ASC", [$this->bio['id']]);

        Response::render('dashboard.blocks', [
            'title' => 'مدیریت بلوک‌های ۱۸ گانه بیو',
            'activePage' => 'blocks',
            'bio' => $this->bio,
            'blocks' => $blocks,
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    private function handleImageUpload(string $fileKey): ?string {
        $file = Request::file($fileKey);
        if ($file && $file['error'] === UPLOAD_ERR_OK) {
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (in_array($ext, ['jpg', 'jpeg', 'png', 'webp', 'gif'])) {
                $filename = 'block_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
                $target = __DIR__ . '/../../uploads/' . $filename;
                if (move_uploaded_file($file['tmp_name'], $target)) {
                    return '/uploads/' . $filename;
                }
            }
        }
        return null;
    }

    private function normalizeUrl(string $url): string {
        $url = trim($url);
        if (!empty($url) && strpos($url, 'http://') !== 0 && strpos($url, 'https://') !== 0 && strpos($url, 'tel:') !== 0 && strpos($url, 'sms:') !== 0 && strpos($url, 'mailto:') !== 0) {
            return 'https://' . $url;
        }
        return $url;
    }

    public function addBlockProcess(): void {
        $type = Request::post('type', 'link');
        $title = trim(Request::post('title', ''));
        $contentData = Request::post('content', []);

        // Normalize URLs
        if (isset($contentData['url'])) $contentData['url'] = $this->normalizeUrl($contentData['url']);
        if (isset($contentData['pay_url'])) $contentData['pay_url'] = $this->normalizeUrl($contentData['pay_url']);
        if (isset($contentData['torob'])) $contentData['torob'] = $this->normalizeUrl($contentData['torob']);
        if (isset($contentData['basalam'])) $contentData['basalam'] = $this->normalizeUrl($contentData['basalam']);
        if (isset($contentData['digikala'])) $contentData['digikala'] = $this->normalizeUrl($contentData['digikala']);
        if (isset($contentData['p1_url'])) $contentData['p1_url'] = $this->normalizeUrl($contentData['p1_url']);

        // Upload single image if present
        $uploadedImg = $this->handleImageUpload('image_file');
        if ($uploadedImg) {
            $contentData['image_url'] = $uploadedImg;
            $contentData['img1'] = $uploadedImg;
        }

        $contentJson = json_encode($contentData, JSON_UNESCAPED_UNICODE);
        $maxSort = Database::fetch("SELECT MAX(sort_order) AS max_sort FROM bio_blocks WHERE bio_page_id = ?", [$this->bio['id']])['max_sort'] ?? 0;

        Database::query("
            INSERT INTO bio_blocks (bio_page_id, type, title, content, sort_order)
            VALUES (?, ?, ?, ?, ?)
        ", [$this->bio['id'], $type, $title, $contentJson, $maxSort + 1]);

        Session::flash('success', 'بلوک جدید با موفقیت اضافه شد.');
        Response::redirect('/dashboard/blocks');
    }

    public function editBlockProcess(): void {
        $blockId = (int)Request::post('block_id', 0);
        $title = trim(Request::post('title', ''));
        $contentData = Request::post('content', []);

        $block = Database::fetch("SELECT * FROM bio_blocks WHERE id = ? AND bio_page_id = ?", [$blockId, $this->bio['id']]);
        if (!$block) {
            Session::flash('error', 'بلوک یافت نشد.');
            Response::redirect('/dashboard/blocks');
        }

        $oldContent = json_decode($block['content'] ?? '{}', true) ?: [];

        // Normalize URLs
        if (isset($contentData['url'])) $contentData['url'] = $this->normalizeUrl($contentData['url']);

        // Keep old image if no new upload
        $uploadedImg = $this->handleImageUpload('image_file');
        if ($uploadedImg) {
            $contentData['image_url'] = $uploadedImg;
        } else {
            if (isset($oldContent['image_url'])) $contentData['image_url'] = $oldContent['image_url'];
        }

        $mergedContent = array_merge($oldContent, $contentData);
        $contentJson = json_encode($mergedContent, JSON_UNESCAPED_UNICODE);

        Database::query("
            UPDATE bio_blocks
            SET title = ?, content = ?
            WHERE id = ? AND bio_page_id = ?
        ", [$title, $contentJson, $blockId, $this->bio['id']]);

        Session::flash('success', 'بلوک با موفقیت ویرایش گردید.');
        Response::redirect('/dashboard/blocks');
    }

    public function deleteBlockProcess(): void {
        $blockId = (int)Request::post('block_id', 0);
        Database::query("DELETE FROM bio_blocks WHERE id = ? AND bio_page_id = ?", [$blockId, $this->bio['id']]);
        Session::flash('success', 'بلوک با موفقیت حذف شد.');
        Response::redirect('/dashboard/blocks');
    }

    public function toggleBlockProcess(): void {
        $blockId = (int)Request::post('block_id', 0);
        $block = Database::fetch("SELECT id, is_active FROM bio_blocks WHERE id = ? AND bio_page_id = ?", [$blockId, $this->bio['id']]);
        if ($block) {
            $newStatus = $block['is_active'] ? 0 : 1;
            Database::query("UPDATE bio_blocks SET is_active = ? WHERE id = ?", [$newStatus, $blockId]);
            Session::flash('success', 'وضعیت نمایش بلوک تغییر یافت.');
        }
        Response::redirect('/dashboard/blocks');
    }

    public function analyticsView(): void {
        $pageViews = $this->bio['views_count'];

        // Last 7 days analytics query
        $last7Days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $cnt = Database::fetch("
                SELECT COUNT(*) AS cnt FROM analytics
                WHERE bio_page_id = ? AND DATE(created_at) = ? AND block_id IS NULL
            ", [$this->bio['id'], $date])['cnt'] ?? 0;
            $last7Days[$date] = $cnt;
        }

        $leads = Database::fetchAll("SELECT * FROM leads WHERE bio_page_id = ? ORDER BY id DESC", [$this->bio['id']]);
        $bookings = Database::fetchAll("SELECT * FROM bookings WHERE bio_page_id = ? ORDER BY id DESC", [$this->bio['id']]);

        Response::render('dashboard.analytics', [
            'title' => 'آمار و گزارش‌های بیو',
            'activePage' => 'analytics',
            'bio' => $this->bio,
            'pageViews' => $pageViews,
            'last7Days' => $last7Days,
            'leads' => $leads,
            'bookings' => $bookings,
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }
}
