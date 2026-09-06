<?php

namespace App\Controllers\Shop;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Cart;
use App\Core\Auth;
use App\Core\Session;
use App\Core\Logger;
use App\Core\Payment\MockGateway;
use App\Core\Payment\ZarinPalGateway;
use App\Models\Order;
use App\Models\License;

class CheckoutController extends Controller
{
    protected Order $orderModel;

    public function __construct()
    {
        parent::__construct();
        $this->orderModel = new Order();
    }

    public function index(Request $request): void
    {
        if (Cart::count() === 0) {
            Session::flash('error', 'سبد خرید شما خالی است.');
            $this->redirect('/store');
        }

        $items = Cart::getItems();
        $total = Cart::total();
        $user = Auth::user();

        $this->render('shop.checkout', [
            'items' => $items,
            'total' => $total,
            'user' => $user,
        ], 'main');
    }

    public function process(Request $request): void
    {
        if (Cart::count() === 0) {
            $this->redirect('/store');
        }

        $gatewayName = $request->post('gateway', 'mock');
        $items = Cart::getItems();
        $total = Cart::total();
        $userId = Auth::id() ?? 2;

        $orderData = $this->orderModel->createOrder($userId, $items, $total, $gatewayName);

        $gateway = ($gatewayName === 'zarinpal') ? new ZarinPalGateway() : new MockGateway();
        $paymentResult = $gateway->requestPayment($orderData);

        if ($paymentResult['success']) {
            $this->redirect($paymentResult['redirect_url']);
        } else {
            Session::flash('error', 'خطا در ارتباط با درگاه پرداخت.');
            $this->redirect('/checkout');
        }
    }

    public function callback(Request $request): void
    {
        $orderId = (int)$request->get('order_id');
        $gatewayName = $request->get('gateway', 'mock');

        $order = $this->orderModel->find($orderId);
        if (!$order) {
            \App\Core\ErrorHandler::renderErrorPage(404, "سفارش یافت نشد", "سفارش مورد نظر در سیستم ثبت نشده است.");
            exit;
        }

        // Idempotency check: if order already completed, don't generate duplicate licenses
        if ($order['status'] === 'completed') {
            Session::flash('success', 'این سفارش قبلاً با موفقیت پرداخت و لایسنس آن صادر گردیده است.');
            $this->redirect('/account/licenses');
        }

        $gateway = ($gatewayName === 'zarinpal') ? new ZarinPalGateway() : new MockGateway();
        $verifyResult = $gateway->verifyPayment($request->all());

        if ($verifyResult['success']) {
            $this->orderModel->update($orderId, [
                'status' => 'completed',
                'transaction_id' => $verifyResult['transaction_id'],
            ]);

            $orderItems = $this->orderModel->getOrderItems($orderId);
            $licenseModel = new License();

            foreach ($orderItems as $item) {
                $licenseModel->generateForOrder($order['user_id'], $item['product_id'], $orderId);
            }

            Cart::clear();
            Logger::info("سفارش شماره {$order['order_number']} با موفقیت پرداخت و لایسنس صادر گردید.", ['order_id' => $orderId]);

            Session::flash('success', 'پرداخت سفارش با موفقیت تایید شد و لایسنس محصول صادر گردید.');
            $this->redirect('/account/licenses');
        } else {
            $this->orderModel->update($orderId, ['status' => 'failed']);
            Session::flash('error', 'پرداخت سفارش ناموفق بود.');
            $this->redirect('/cart');
        }
    }
}
