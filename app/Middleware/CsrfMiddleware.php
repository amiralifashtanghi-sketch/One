<?php

namespace App\Middleware;

use App\Core\Csrf;
use App\Core\Request;
use App\Core\ErrorHandler;

class CsrfMiddleware
{
    public function handle(Request $request): void
    {
        if (in_array($request->method(), ['POST', 'PUT', 'DELETE'], true)) {
            $token = $request->post(Csrf::getTokenName());
            if (!Csrf::verify($token)) {
                ErrorHandler::renderErrorPage(419, "اعتبار فرم منقضی شده است (CSRF Error)", "لطفاً صفحه را بازخوانی کرده و مجدداً تلاش کنید.");
                exit;
            }
        }
    }
}
