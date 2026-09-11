<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Request;
use App\Core\Response;

class GuestMiddleware
{
    public function handle(Request $request): void
    {
        if (Auth::check()) {
            if (Auth::role() === 'admin' || Auth::role() === 'operator') {
                Response::redirect('/admin');
            } else {
                Response::redirect('/account');
            }
        }
    }
}
