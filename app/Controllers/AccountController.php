<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Auth;
use App\Core\RBAC;
use App\Models\Order;
use App\Models\License;

class AccountController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!Auth::check()) {
            \App\Core\Response::redirect('/login');
        }
    }

    public function index(Request $request): void
    {
        $user = Auth::user();
        $userId = Auth::id();

        $orderModel = new Order();
        $licenseModel = new License();

        $orders = $orderModel->where('user_id', $userId);
        $licenses = $licenseModel->getLicensesWithDetails($userId);

        $this->render('account.dashboard', [
            'user' => $user,
            'orders' => $orders,
            'licenses' => $licenses,
        ], 'main');
    }

    public function orders(Request $request): void
    {
        $userId = Auth::id();
        $orderModel = new Order();
        $orders = $orderModel->where('user_id', $userId);

        $this->render('account.orders', ['orders' => $orders], 'main');
    }

    public function licenses(Request $request): void
    {
        $userId = Auth::id();
        $licenseModel = new License();
        $licenses = $licenseModel->getLicensesWithDetails($userId);

        $this->render('account.licenses', ['licenses' => $licenses], 'main');
    }
}
