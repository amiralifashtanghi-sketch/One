<?php

namespace App\Controllers;

use App\Core\Database;
use App\Core\Helper;
use App\Core\Request;
use App\Core\Response;
use App\Core\Session;
use App\Services\OtpService;

class AuthController {
    private OtpService $otpService;

    public function __construct() {
        $this->otpService = new OtpService();
    }

    public function loginView(): void {
        if (Session::has('user_id')) {
            $role = Session::get('user_role');
            if ($role === 'admin') Response::redirect('/admin');
            elseif ($role === 'seller') Response::redirect('/seller');
            else Response::redirect('/dashboard');
        }

        Response::render('auth.login', [
            'title' => 'ورود به سامانه بیو لینک',
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function loginProcess(): void {
        $phone = Request::post('phone', '');
        $password = Request::post('password', '');

        $normPhone = Helper::normalizePhone($phone);
        if (!$normPhone) {
            Session::flash('error', 'شماره همراه وارد شده معتبر نیست.');
            Response::redirect('/login');
        }

        $user = Database::fetch("SELECT * FROM users WHERE phone = ?", [$normPhone]);
        if (!$user || !Helper::verifyPassword($password, $user['password'])) {
            Session::flash('error', 'شماره همراه یا رمز عبور اشتباه است.');
            Response::redirect('/login');
        }

        if ($user['is_blocked']) {
            Session::flash('error', 'حساب کاربری شما توسط مدیر مسدود شده است.');
            Response::redirect('/login');
        }

        // Set Session
        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name'] ?? $user['phone']);
        Session::set('user_phone', $user['phone']);

        if ($user['role'] === 'admin') {
            Response::redirect('/admin');
        } elseif ($user['role'] === 'seller') {
            Response::redirect('/seller');
        } else {
            Response::redirect('/dashboard');
        }
    }

    public function sendOtpAjax(): void {
        $phone = Request::input('phone', '');
        $res = $this->otpService->sendOtp($phone);
        Response::json($res);
    }

    public function loginWithOtpProcess(): void {
        $phone = Request::post('phone', '');
        $code = Request::post('otp_code', '');

        $normPhone = Helper::normalizePhone($phone);
        if (!$normPhone) {
            Session::flash('error', 'شماره همراه معتبر نیست.');
            Response::redirect('/login');
        }

        if (!$this->otpService->verifyOtp($normPhone, $code)) {
            Session::flash('error', 'کد تایید پیامکی نامعتبر است یا منقضی شده است.');
            Response::redirect('/login');
        }

        $user = Database::fetch("SELECT * FROM users WHERE phone = ?", [$normPhone]);
        if (!$user) {
            Session::flash('error', 'حساب کاربری با این شماره پیدا نشد.');
            Response::redirect('/login');
        }

        if ($user['is_blocked']) {
            Session::flash('error', 'حساب کاربری شما مسدود شده است.');
            Response::redirect('/login');
        }

        Session::set('user_id', $user['id']);
        Session::set('user_role', $user['role']);
        Session::set('user_name', $user['name'] ?? $user['phone']);
        Session::set('user_phone', $user['phone']);

        if ($user['role'] === 'admin') Response::redirect('/admin');
        elseif ($user['role'] === 'seller') Response::redirect('/seller');
        else Response::redirect('/dashboard');
    }

    public function forgotPasswordView(): void {
        Response::render('auth.forgot-password', [
            'title' => 'بازیابی رمز عبور پیامکی',
            'error' => Session::flash('error'),
            'success' => Session::flash('success')
        ]);
    }

    public function resetPasswordProcess(): void {
        $phone = Request::post('phone', '');
        $code = Request::post('otp_code', '');
        $newPass = Request::post('new_password', '');

        $res = $this->otpService->resetPasswordWithOtp($phone, $code, $newPass);
        if ($res['success']) {
            Session::flash('success', $res['message']);
            Response::redirect('/login');
        } else {
            Session::flash('error', $res['message']);
            Response::redirect('/forgot-password');
        }
    }

    public function logout(): void {
        Session::destroy();
        Response::redirect('/login');
    }
}
