<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\Helper;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\SmsClient;

class AdminController {
    public function __construct() {
        // Ensure user is admin
        if (!Session::has('user_id') || Session::get('user_role') !== 'admin') {
            Session::flash('error', 'دسترسی غیرمجاز. شما باید به عنوان مدیر کل وارد شوید.');
            Response::redirect('/login');
        }
    }

    public function dashboard(): void {
        $totalCards = Database::fetch("SELECT COUNT(*) AS cnt FROM cards")['cnt'] ?? 0;
        $assignedCards = Database::fetch("SELECT COUNT(*) AS cnt FROM cards WHERE status = 'assigned'")['cnt'] ?? 0;
        $unassignedCards = Database::fetch("SELECT COUNT(*) AS cnt FROM cards WHERE status = 'unassigned'")['cnt'] ?? 0;

        $totalUsers = Database::fetch("SELECT COUNT(*) AS cnt FROM users WHERE role = 'user'")['cnt'] ?? 0;
        $totalSellers = Database::fetch("SELECT COUNT(*) AS cnt FROM users WHERE role = 'seller'")['cnt'] ?? 0;
        $totalViews = Database::fetch("SELECT SUM(views_count) AS cnt FROM bio_pages")['cnt'] ?? 0;
        $totalLeads = Database::fetch("SELECT COUNT(*) AS cnt FROM leads")['cnt'] ?? 0;
        $totalBookings = Database::fetch("SELECT COUNT(*) AS cnt FROM bookings")['cnt'] ?? 0;

        $recentCards = Database::fetchAll("
            SELECT c.*, u.phone AS user_phone, s.phone AS seller_phone
            FROM cards c
            LEFT JOIN users u ON c.assigned_user_id = u.id
            LEFT JOIN users s ON c.seller_user_id = s.id
            ORDER BY c.id DESC LIMIT 10
        ");

        Response::render('admin.dashboard', [
            'title' => 'داشبورد اصلی مدیر کل',
            'activePage' => 'dashboard',
            'stats' => [
                'total_cards' => $totalCards,
                'assigned_cards' => $assignedCards,
                'unassigned_cards' => $unassignedCards,
                'total_users' => $totalUsers,
                'total_sellers' => $totalSellers,
                'total_views' => $totalViews,
                'total_leads' => $totalLeads,
                'total_bookings' => $totalBookings
            ],
            'recentCards' => $recentCards,
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function cards(): void {
        $cards = Database::fetchAll("
            SELECT c.*, u.phone AS user_phone, b.username AS bio_username, s.phone AS seller_phone
            FROM cards c
            LEFT JOIN users u ON c.assigned_user_id = u.id
            LEFT JOIN bio_pages b ON u.id = b.user_id
            LEFT JOIN users s ON c.seller_user_id = s.id
            ORDER BY c.id DESC
        ");

        $sellers = Database::fetchAll("SELECT id, phone, name FROM users WHERE role = 'seller'");

        Response::render('admin.cards', [
            'title' => 'مدیریت کارت‌ها و QR کدها',
            'activePage' => 'cards',
            'cards' => $cards,
            'sellers' => $sellers,
            'appUrl' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function generateCardsProcess(): void {
        $count = (int)Request::post('count', 1);
        if ($count < 1) $count = 1;
        if ($count > 100) $count = 100;

        $generated = 0;
        for ($i = 0; $i < $count; $i++) {
            $code = 'card_' . Helper::randomCode(6);
            try {
                Database::query("INSERT INTO cards (card_code, status) VALUES (?, 'unassigned')", [$code]);
                $generated++;
            } catch (\Exception $e) {
                // Retry if code collision
            }
        }

        Session::flash('success', "تعداد {$generated} کارت/کیو آر کد جدید بدون مالک با موفقیت ساخته شد.");
        Response::redirect('/admin/cards');
    }

    public function updateCardStatusProcess(): void {
        $cardId = (int)Request::post('card_id', 0);
        $status = Request::post('status', '');

        if ($cardId > 0 && in_array($status, ['unassigned', 'assigned', 'blocked'])) {
            Database::query("UPDATE cards SET status = ? WHERE id = ?", [$status, $cardId]);
            Session::flash('success', 'وضعیت کارت با موفقیت بروزرسانی شد.');
        } else {
            Session::flash('error', 'درخواست نامعتبر است.');
        }
        Response::redirect('/admin/cards');
    }

    public function reassignCardProcess(): void {
        $cardId = (int)Request::post('card_id', 0);
        $newPhone = Request::post('new_phone', '');

        $normPhone = Helper::normalizePhone($newPhone);
        if (!$normPhone) {
            Session::flash('error', 'شماره همراه جدید معتبر نیست.');
            Response::redirect('/admin/cards');
        }

        $user = Database::fetch("SELECT id FROM users WHERE phone = ?", [$normPhone]);
        if (!$user) {
            Session::flash('error', 'کاربری با این شماره همراه یافت نشد.');
            Response::redirect('/admin/cards');
        }

        Database::query("UPDATE cards SET assigned_user_id = ?, status = 'assigned', assigned_at = NOW() WHERE id = ?", [$user['id'], $cardId]);
        Session::flash('success', 'مالک کارت با موفقیت به شماره جدید تغییر یافت.');
        Response::redirect('/admin/cards');
    }

    public function users(): void {
        $users = Database::fetchAll("
            SELECT u.*,
            (SELECT COUNT(*) FROM cards WHERE seller_user_id = u.id AND status = 'assigned') AS sales_count,
            b.username AS bio_username
            FROM users u
            LEFT JOIN bio_pages b ON u.id = b.user_id
            ORDER BY u.id DESC
        ");

        Response::render('admin.users', [
            'title' => 'مدیریت کاربران و فروشندگان',
            'activePage' => 'users',
            'users' => $users,
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function toggleSellerProcess(): void {
        $userId = (int)Request::post('user_id', 0);
        $user = Database::fetch("SELECT id, role FROM users WHERE id = ?", [$userId]);

        if ($user && $user['role'] !== 'admin') {
            $newRole = ($user['role'] === 'seller') ? 'user' : 'seller';
            Database::query("UPDATE users SET role = ? WHERE id = ?", [$newRole, $userId]);
            Session::flash('success', 'نقش کاربر با موفقیت تغییر کرد.');
        } else {
            Session::flash('error', 'امکان تغییر نقش این کاربر وجود ندارد.');
        }

        Response::redirect('/admin/users');
    }

    public function toggleUserBlockProcess(): void {
        $userId = (int)Request::post('user_id', 0);
        $user = Database::fetch("SELECT id, role, is_blocked FROM users WHERE id = ?", [$userId]);

        if ($user && $user['role'] !== 'admin') {
            $newStatus = $user['is_blocked'] ? 0 : 1;
            Database::query("UPDATE users SET is_blocked = ? WHERE id = ?", [$newStatus, $userId]);
            Session::flash('success', 'وضعیت مسدودی کاربر با موفقیت تغییر یافت.');
        } else {
            Session::flash('error', 'امکان مسدودسازی مدیر کل وجود ندارد.');
        }

        Response::redirect('/admin/users');
    }

    public function bios(): void {
        $bios = Database::fetchAll("
            SELECT b.*, u.phone, u.name AS user_name,
            (SELECT COUNT(*) FROM bio_blocks WHERE bio_page_id = b.id) AS blocks_count
            FROM bio_pages b
            JOIN users u ON b.user_id = u.id
            ORDER BY b.id DESC
        ");

        Response::render('admin.bios', [
            'title' => 'مدیریت صفحات بیو',
            'activePage' => 'bios',
            'bios' => $bios,
            'appUrl' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function editBio(array $params = []): void {
        $bioId = (int)($params['id'] ?? 0);
        $bio = Database::fetch("
            SELECT b.*, u.phone, u.name AS user_name
            FROM bio_pages b
            JOIN users u ON b.user_id = u.id
            WHERE b.id = ?
        ", [$bioId]);

        if (!$bio) {
            Session::flash('error', 'صفحه بیو مورد نظر پیدا نشد.');
            Response::redirect('/admin/bios');
        }

        $blocks = Database::fetchAll("SELECT * FROM bio_blocks WHERE bio_page_id = ? ORDER BY sort_order ASC, id ASC", [$bioId]);

        Response::render('admin.bio_edit', [
            'title' => 'ویرایش کامل صفحه بیو: ' . $bio['username'],
            'activePage' => 'bios',
            'bio' => $bio,
            'blocks' => $blocks,
            'appUrl' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function saveBioProcess(): void {
        $bioId = (int)Request::post('bio_id', 0);
        $username = Helper::slugify(Request::post('username', ''));
        $title = trim(Request::post('title', ''));
        $description = trim(Request::post('description', ''));

        if ($bioId <= 0 || empty($username)) {
            Session::flash('error', 'اطلاعات وارد شده نامعتبر است.');
            Response::redirect('/admin/bios');
        }

        // Check uniqueness
        $existing = Database::fetch("SELECT id FROM bio_pages WHERE username = ? AND id != ?", [$username, $bioId]);
        if ($existing) {
            Session::flash('error', 'این یوزرنیم قبلاً ثبت شده است.');
            Response::redirect('/admin/bios/edit/' . $bioId);
        }

        Database::query("
            UPDATE bio_pages
            SET username = ?, title = ?, description = ?
            WHERE id = ?
        ", [$username, $title, $description, $bioId]);

        Session::flash('success', 'اطلاعات صفحه بیو با موفقیت توسط مدیر بروزرسانی شد.');
        Response::redirect('/admin/bios/edit/' . $bioId);
    }

    public function settings(): void {
        $settingsRows = Database::fetchAll("SELECT * FROM settings");
        $settings = [];
        foreach ($settingsRows as $row) {
            $settings[$row['setting_key']] = $row['setting_value'];
        }

        Response::render('admin.settings', [
            'title' => 'تنظیمات عمومی سامانه و پیامک SMS.ir',
            'activePage' => 'settings',
            'settings' => $settings,
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function saveSettingsProcess(): void {
        $inputs = [
            'sms_api_key' => trim(Request::post('sms_api_key', '')),
            'sms_template_id' => trim(Request::post('sms_template_id', '')),
            'sms_param_name' => trim(Request::post('sms_param_name', 'CODE')),
            'site_title' => trim(Request::post('site_title', 'سامانه کارت و بیو لینک اختصاصی'))
        ];

        $stmt = Database::getInstance()->prepare("INSERT INTO settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
        foreach ($inputs as $key => $val) {
            $stmt->execute([$key, $val]);
        }

        Session::flash('success', 'تنظیمات سامانه با موفقیت ذخیره گردید.');
        Response::redirect('/admin/settings');
    }

    public function testSmsProcess(): void {
        $testPhone = Request::post('test_phone', '');
        $smsClient = new SmsClient();
        $res = $smsClient->sendOtp($testPhone, '1234');

        if ($res['success']) {
            Session::flash('success', 'تست ارسال پیامک با موفقیت انجام شد: ' . $res['message']);
        } else {
            Session::flash('error', 'تست ارسال پیامک ناموفق بود: ' . $res['message']);
        }

        Response::redirect('/admin/settings');
    }
}
