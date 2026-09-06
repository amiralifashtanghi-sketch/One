<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\Session;

class AuthController extends Controller
{
    public function showLogin(Request $request): void
    {
        if (Auth::check()) {
            if (Auth::role() === 'admin' || Auth::role() === 'operator') {
                $this->redirect('/admin');
            } else {
                $this->redirect('/account');
            }
        }

        $this->render('auth.login', [], 'main');
    }

    public function login(Request $request): void
    {
        $identifier = $request->post('identifier');
        $password = $request->post('password');

        $user = Auth::attempt((string)$identifier, (string)$password);

        if ($user) {
            Session::flash('success', 'با موفقیت وارد حساب کاربری شدید.');
            if (in_array($user['role'], ['admin', 'operator'], true)) {
                $this->redirect('/admin');
            } else {
                $this->redirect('/account');
            }
        } else {
            Session::flash('error', 'شماره موبایل/ایمیل یا رمز عبور اشتباه است.');
            $this->redirect('/login');
        }
    }

    public function logout(Request $request): void
    {
        Auth::logout();
        Session::flash('success', 'با موفقیت از سیستم خارج شدید.');
        $this->redirect('/');
    }
}
