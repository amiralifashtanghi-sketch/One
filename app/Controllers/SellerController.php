<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\Helper;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\SmsClient;

class SellerController {
    public function __construct() {
        if (!Session::has('user_id') || !in_array(Session::get('user_role'), ['seller', 'admin'])) {
            Session::flash('error', 'دسترسی غیرمجاز. شما باید دسترسی فروشندگی داشته باشید.');
            Response::redirect('/login');
        }
    }

    public function dashboard(): void {
        $sellerId = Session::get('user_id');

        $soldCount = Database::fetch("
            SELECT COUNT(*) AS cnt FROM cards WHERE seller_user_id = ? AND status = 'assigned'
        ", [$sellerId])['cnt'] ?? 0;

        $recentAssigned = Database::fetchAll("
            SELECT c.*, u.phone AS buyer_phone, u.name AS buyer_name, b.username AS bio_username
            FROM cards c
            JOIN users u ON c.assigned_user_id = u.id
            LEFT JOIN bio_pages b ON u.id = b.user_id
            WHERE c.seller_user_id = ? AND c.status = 'assigned'
            ORDER BY c.assigned_at DESC LIMIT 10
        ", [$sellerId]);

        Response::render('seller.dashboard', [
            'title' => 'داشبورد اختصاصی فروشنده',
            'activePage' => 'dashboard',
            'soldCount' => $soldCount,
            'recentAssigned' => $recentAssigned,
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function assignView(): void {
        Response::render('seller.assign', [
            'title' => 'تحویل کارت و فعال‌سازی برای خریدار',
            'activePage' => 'assign',
            'appUrl' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function assignProcess(): void {
        $sellerId = Session::get('user_id');
        $cardInput = trim(Request::post('card_code', ''));
        $buyerPhone = trim(Request::post('buyer_phone', ''));
        $buyerPass = trim(Request::post('buyer_password', ''));

        // Clean card code if full URL was scanned/entered
        if (strpos($cardInput, '/') !== false) {
            $parts = explode('/', trim($cardInput, '/'));
            $cardInput = end($parts);
        }

        if (empty($cardInput)) {
            Session::flash('error', 'لطفاً کد یا لینک کارت را وارد کنید.');
            Response::redirect('/seller/assign');
        }

        $normPhone = Helper::normalizePhone($buyerPhone);
        if (!$normPhone) {
            Session::flash('error', 'شماره همراه خریدار معتبر نیست.');
            Response::redirect('/seller/assign');
        }

        if (strlen($buyerPass) < 4) {
            Session::flash('error', 'رمز عبور خریدار باید حداقل ۴ کاراکتر باشد.');
            Response::redirect('/seller/assign');
        }

        // Find card
        $card = Database::fetch("SELECT * FROM cards WHERE card_code = ?", [$cardInput]);
        if (!$card) {
            Session::flash('error', 'کارت با این کد پیدا نشد.');
            Response::redirect('/seller/assign');
        }

        if ($card['status'] === 'assigned' && $card['assigned_user_id'] !== null) {
            Session::flash('error', 'این کارت قبلاً به خریدار دیگری تخصیص یافته است.');
            Response::redirect('/seller/assign');
        }

        if ($card['status'] === 'blocked') {
            Session::flash('error', 'این کارت توسط مدیر مسدود شده است.');
            Response::redirect('/seller/assign');
        }

        Database::beginTransaction();
        try {
            // Find or create buyer user
            $buyer = Database::fetch("SELECT id FROM users WHERE phone = ?", [$normPhone]);
            $hashedPass = Helper::hashPassword($buyerPass);

            if ($buyer) {
                $buyerId = $buyer['id'];
                // Update buyer password
                Database::query("UPDATE users SET password = ? WHERE id = ?", [$hashedPass, $buyerId]);
            } else {
                Database::query("INSERT INTO users (phone, password, role) VALUES (?, ?, 'user')", [$normPhone, $hashedPass]);
                $buyerId = Database::lastInsertId();
            }

            // Create bio_page if not exists
            $bio = Database::fetch("SELECT id, username FROM bio_pages WHERE user_id = ?", [$buyerId]);
            if (!$bio) {
                $username = 'u' . substr($normPhone, -4) . rand(100, 999);
                Database::query("INSERT INTO bio_pages (user_id, username, title) VALUES (?, ?, 'صفحه بیو اختصاصی')", [$buyerId, $username]);
                $bioUsername = $username;
            } else {
                $bioUsername = $bio['username'];
            }

            // Assign Card to Buyer & record Seller
            Database::query("
                UPDATE cards
                SET assigned_user_id = ?, seller_user_id = ?, status = 'assigned', assigned_at = NOW()
                WHERE id = ?
            ", [$buyerId, $sellerId, $card['id']]);

            Database::commit();

            // Try sending welcome SMS
            $smsClient = new SmsClient();
            $appUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
            $smsClient->sendOtp($normPhone, $buyerPass);

            Session::flash('success', "کارت با موفقیت به شماره {$normPhone} تخصیص یافت. لینک بیو: {$appUrl}/{$bioUsername}");
            Response::redirect('/seller/history');
        } catch (\Exception $e) {
            Database::rollBack();
            Session::flash('error', 'خطا در تخصیص کارت: ' . $e->getMessage());
            Response::redirect('/seller/assign');
        }
    }

    public function history(): void {
        $sellerId = Session::get('user_id');

        $assignedCards = Database::fetchAll("
            SELECT c.*, u.phone AS buyer_phone, u.name AS buyer_name, b.username AS bio_username
            FROM cards c
            JOIN users u ON c.assigned_user_id = u.id
            LEFT JOIN bio_pages b ON u.id = b.user_id
            WHERE c.seller_user_id = ? AND c.status = 'assigned'
            ORDER BY c.assigned_at DESC
        ", [$sellerId]);

        Response::render('seller.history', [
            'title' => 'تاریخچه کارت‌های فروخته‌شده',
            'activePage' => 'history',
            'assignedCards' => $assignedCards,
            'appUrl' => (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'],
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }
}
