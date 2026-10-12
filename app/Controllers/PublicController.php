<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\Helper;
use App\Core\Request;
use App\Core\Response;

class PublicController {
    public function handleSlug(string $slug): void {
        $slug = Helper::sanitize($slug);

        // 1. Check if slug matches a card code
        $card = Database::fetch("SELECT * FROM cards WHERE card_code = ?", [$slug]);
        if ($card) {
            if ($card['status'] === 'assigned' && !empty($card['assigned_user_id'])) {
                // Find or auto-create buyer's bio page username
                $bio = Database::fetch("SELECT username FROM bio_pages WHERE user_id = ?", [$card['assigned_user_id']]);

                if (!$bio || empty($bio['username'])) {
                    $user = Database::fetch("SELECT phone FROM users WHERE id = ?", [$card['assigned_user_id']]);
                    $phone = $user['phone'] ?? (string)rand(1000, 9999);
                    $username = 'u' . substr($phone, -4) . rand(100, 999);
                    Database::query("INSERT INTO bio_pages (user_id, username, title) VALUES (?, ?, 'صفحه بیو اختصاصی')", [$card['assigned_user_id'], $username]);
                    $bioUsername = $username;
                } else {
                    $bioUsername = $bio['username'];
                }

                // Redirect 301 to the buyer's bio username page!
                Response::redirect('/' . $bioUsername);
                return;
            }

            // Card is unassigned or blocked
            Response::render('public.card_unassigned', [
                'title' => 'کارت ویزیت آنلاین',
                'card' => $card
            ]);
            return;
        }

        // 2. Check if slug matches a bio page username
        $bio = Database::fetch("SELECT * FROM bio_pages WHERE username = ?", [$slug]);
        if (!$bio) {
            Response::status(404);
            Response::render('404', ['title' => 'صفحه پیدا نشد']);
            return;
        }

        // Increment total view count
        Database::query("UPDATE bio_pages SET views_count = views_count + 1 WHERE id = ?", [$bio['id']]);

        // Log analytics entry
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
        Database::query("INSERT INTO analytics (bio_page_id, ip_address, user_agent) VALUES (?, ?, ?)", [$bio['id'], $ip, $ua]);

        // Get active blocks
        $blocks = Database::fetchAll("
            SELECT * FROM bio_blocks
            WHERE bio_page_id = ? AND is_active = 1
            ORDER BY sort_order ASC, id ASC
        ", [$bio['id']]);

        Response::render('public.bio_view', [
            'title' => $bio['title'] ?: $bio['username'],
            'bio' => $bio,
            'blocks' => $blocks
        ]);
    }

    public function trackClick(): void {
        $blockId = (int)Request::post('block_id', 0);
        if ($blockId > 0) {
            Database::query("UPDATE bio_blocks SET clicks_count = clicks_count + 1 WHERE id = ?", [$blockId]);
            $block = Database::fetch("SELECT bio_page_id FROM bio_blocks WHERE id = ?", [$blockId]);
            if ($block) {
                $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
                $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
                Database::query("INSERT INTO analytics (bio_page_id, block_id, ip_address, user_agent) VALUES (?, ?, ?, ?)", [$block['bio_page_id'], $blockId, $ip, $ua]);
            }
        }
        Response::json(['success' => true]);
    }

    public function submitLead(): void {
        $bioId = (int)Request::post('bio_page_id', 0);
        $name = trim(Request::post('name', ''));
        $phone = Helper::normalizePhone(Request::post('phone', '')) ?? Request::post('phone', '');
        $email = trim(Request::post('email', ''));
        $message = trim(Request::post('message', ''));

        if ($bioId > 0 && (!empty($phone) || !empty($name))) {
            Database::query("
                INSERT INTO leads (bio_page_id, name, phone, email, message)
                VALUES (?, ?, ?, ?, ?)
            ", [$bioId, $name, $phone, $email, $message]);

            Response::json(['success' => true, 'message' => 'اطلاعات شما با موفقیت ثبت شد. با شما تماس خواهیم گرفت.']);
        } else {
            Response::json(['success' => false, 'message' => 'لطفاً نام یا شماره تماس خود را وارد کنید.']);
        }
    }

    public function submitBooking(): void {
        $bioId = (int)Request::post('bio_page_id', 0);
        $name = trim(Request::post('name', ''));
        $phone = Helper::normalizePhone(Request::post('phone', '')) ?? Request::post('phone', '');
        $date = trim(Request::post('date', ''));
        $time = trim(Request::post('time', ''));
        $notes = trim(Request::post('notes', ''));

        if ($bioId > 0 && !empty($name) && !empty($phone) && !empty($date)) {
            Database::query("
                INSERT INTO bookings (bio_page_id, name, phone, booking_date, booking_time, notes)
                VALUES (?, ?, ?, ?, ?, ?)
            ", [$bioId, $name, $phone, $date, $time, $notes]);

            Response::json(['success' => true, 'message' => 'نوبت شما با موفقیت ثبت گردید.']);
        } else {
            Response::json(['success' => false, 'message' => 'لطفاً تمامی فیلدهای رزرو را تکمیل کنید.']);
        }
    }
}
