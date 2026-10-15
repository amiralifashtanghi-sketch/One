<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\RBAC;
use App\Core\Request;
use App\Core\ErrorHandler;

class AdminMiddleware
{
    public function handle(Request $request): void
    {
        if (!Auth::check()) {
            \App\Core\Response::redirect('/login');
        }

        if (!RBAC::can('access_admin')) {
            ErrorHandler::renderErrorPage(403, "عدم دسترسی به پنل مدیریت", "شما مجوزهای لازم جهت ورود به بخش مدیریت را ندارید.");
            exit;
        }
    }
}
