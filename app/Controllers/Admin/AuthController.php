<?php
namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Security;
use App\Core\SessionManager;
use App\Core\Database;

class AuthController extends Controller {
    public function loginForm(): void {
        SessionManager::start();
        if (SessionManager::get('admin_user')) {
            $this->redirect('/admin');
        }

        $csrfToken = Security::generateCSRFToken();
        $this->render('admin/login', [
            'pageTitle' => 'ورود به پنل مدیریت EAFD',
            'csrfToken' => $csrfToken,
            'error' => SessionManager::get('login_error')
        ], '');
        SessionManager::remove('login_error');
    }

    public function login(): void {
        SessionManager::start();

        $token = $_POST['csrf_token'] ?? '';
        if (!Security::verifyCSRFToken($token)) {
            SessionManager::set('login_error', 'نشست نامعتبر است. لطفاً مجدداً تلاش کنید.');
            $this->redirect('/admin/login');
        }

        if (!Security::checkRateLimit('admin_login', 5, 300)) {
            SessionManager::set('login_error', 'تعداد تلاش‌های ناموفق بیش از حد مجاز است. ۵ دقیقه صبر کنید.');
            $this->redirect('/admin/login');
        }

        $username = Security::sanitizeString($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';

        if (empty($username) || empty($password)) {
            SessionManager::set('login_error', 'نام کاربری و رمز عبور الزامی است.');
            $this->redirect('/admin/login');
        }

        try {
            $user = Database::fetch("SELECT * FROM eafd_users WHERE username = :u LIMIT 1", ['u' => $username]);
            if ($user && Security::verifyPassword($password, $user['password'])) {
                SessionManager::regenerate();
                SessionManager::set('admin_user', [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'display_name' => $user['display_name'],
                    'role' => $user['role']
                ]);
                $this->redirect('/admin');
            } else {
                SessionManager::set('login_error', 'نام کاربری یا رمز عبور اشتباه است.');
                $this->redirect('/admin/login');
            }
        } catch (\Exception $e) {
            SessionManager::set('login_error', 'خطا در برقراری ارتباط با سیستم.');
            $this->redirect('/admin/login');
        }
    }

    public function logout(): void {
        SessionManager::destroy();
        $this->redirect('/admin/login');
    }
}
