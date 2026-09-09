<?php

namespace App\Controllers\Shop;

use App\Core\Controller;
use App\Core\Request;
use App\Core\Cart;
use App\Core\Payment\PaymentGatewayManager;
use App\Models\Product;
use App\Core\Database;
use Exception;

class CheckoutController extends Controller
{
    protected PaymentGatewayManager $gatewayManager;

    public function __construct()
    {
        parent::__construct();
        $this->gatewayManager = new PaymentGatewayManager();
    }

    public function index(Request $request): void
    {
        $items = Cart::getItems();
        $total = Cart::getTotal();
        $gateways = $this->gatewayManager->getAvailableGateways();

        $this->render('shop.checkout', [
            'items' => $items,
            'total' => $total,
            'gateways' => $gateways,
        ], 'main');
    }

    public function process(Request $request): void
    {
        $items = Cart::getItems();
        if (empty($items)) {
            header('Location: /cart');
            exit;
        }

        $customerName = trim($request->post('name', ''));
        $customerEmail = trim($request->post('email', ''));
        $customerPhone = trim($request->post('phone', ''));
        $gatewayId = trim($request->post('gateway', 'mock'));

        if (empty($customerName) || empty($customerEmail)) {
            $_SESSION['checkout_error'] = 'لطفاً نام و ایمیل خود را وارد نمایید.';
            header('Location: /checkout');
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        $totalAmount = Cart::getTotal();

        Database::beginTransaction();
        try {
            // 1. Create Order
            $orderSql = "INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, total_amount, status, created_at) VALUES (?, ?, ?, ?, ?, 'pending', CURRENT_TIMESTAMP)";
            Database::query($orderSql, [$userId, $customerName, $customerEmail, $customerPhone, $totalAmount]);
            $orderId = (int)Database::lastInsertId();

            // 2. Insert Order Items
            $productModel = new Product();
            foreach ($items as $item) {
                $p = $productModel->find($item['product_id']);
                $price = $p['sale_price'] ?? $p['price'];
                $itemSql = "INSERT INTO order_items (order_id, product_id, product_name, price, quantity) VALUES (?, ?, ?, ?, ?)";
                Database::query($itemSql, [$orderId, $p['id'], $p['title'], $price, $item['quantity']]);
            }

            // 3. Initiate Payment Gateway
            $gateway = $this->gatewayManager->getGateway($gatewayId);
            $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
            $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
            $callbackUrl = $protocol . '://' . $host . '/checkout/callback?order_id=' . $orderId . '&gateway=' . $gatewayId;

            $payReq = $gateway->request([
                'order_id' => $orderId,
                'amount' => $totalAmount,
                'customer_email' => $customerEmail,
                'customer_phone' => $customerPhone,
            ], $callbackUrl);

            if (!$payReq['success']) {
                Database::rollBack();
                $_SESSION['checkout_error'] = $payReq['error'] ?? 'خطا در اتصال به درگاه پرداخت.';
                header('Location: /checkout');
                exit;
            }

            // 4. Save Payment Transaction
            $txSql = "INSERT INTO transactions (order_id, gateway, transaction_id, amount, status, created_at) VALUES (?, ?, ?, ?, 'pending', CURRENT_TIMESTAMP)";
            Database::query($txSql, [$orderId, $gatewayId, $payReq['transaction_id'], $totalAmount]);

            Database::commit();
            Cart::clear();

            header('Location: ' . $payReq['redirect_url']);
            exit;

        } catch (Exception $e) {
            Database::rollBack();
            $_SESSION['checkout_error'] = 'خطای سیستم در پردازش سفارش: ' . $e->getMessage();
            header('Location: /checkout');
            exit;
        }
    }

    public function callback(Request $request): void
    {
        $orderId = (int)$request->get('order_id', 0);
        $gatewayId = trim($request->get('gateway', 'mock'));

        $order = Database::fetch("SELECT * FROM orders WHERE id = ?", [$orderId]);
        if (!$order) {
            \App\Core\ErrorHandler::renderErrorPage(404, "سفارش یافت نشد", "سفارش مورد نظر در سیستم وجود ندارد.");
            exit;
        }

        // Idempotency check: If order is already paid, do not re-fulfill
        if ($order['status'] === 'paid') {
            $this->render('shop.thankyou', ['order' => $order, 'message' => 'این سفارش قبلاً با موفقیت پرداخت شده است.'], 'main');
            return;
        }

        $tx = Database::fetch("SELECT * FROM transactions WHERE order_id = ? AND gateway = ? ORDER BY id DESC LIMIT 1", [$orderId, $gatewayId]);
        if (!$tx) {
            \App\Core\ErrorHandler::renderErrorPage(400, "تراکنش یافت نشد", "اطلاعات تراکنش معتبر نمی‌باشد.");
            exit;
        }

        $gateway = $this->gatewayManager->getGateway($gatewayId);
        $verifyRes = $gateway->verify($request->all(), ['amount' => $order['total_amount']]);

        if ($verifyRes['success']) {
            Database::beginTransaction();
            try {
                // Update Transaction
                Database::query("UPDATE transactions SET status = 'success', reference_id = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?", [$verifyRes['reference_id'] ?? '', $tx['id']]);

                // Update Order Status
                Database::query("UPDATE orders SET status = 'paid', updated_at = CURRENT_TIMESTAMP WHERE id = ?", [$orderId]);

                // Fulfill Order & Generate Unique Licenses
                $items = Database::fetchAll("SELECT * FROM order_items WHERE order_id = ?", [$orderId]);
                $productModel = new Product();

                foreach ($items as $item) {
                    $product = $productModel->find($item['product_id']);
                    if ($product) {
                        $this->generateLicenseForOrder($order, $product);
                    }
                }

                Database::commit();
                $this->render('shop.thankyou', ['order' => $order, 'reference_id' => $verifyRes['reference_id'] ?? ''], 'main');

            } catch (Exception $e) {
                Database::rollBack();
                \App\Core\ErrorHandler::renderErrorPage(500, "خطای صدور لایسنس", "پرداخت تایید شد اما در صدور لایسنس خطایی رخ داد: " . $e->getMessage());
            }
        } else {
            Database::query("UPDATE transactions SET status = 'failed', updated_at = CURRENT_TIMESTAMP WHERE id = ?", [$tx['id']]);
            Database::query("UPDATE orders SET status = 'failed', updated_at = CURRENT_TIMESTAMP WHERE id = ?", [$orderId]);
            $this->render('shop.payment_failed', ['error' => $verifyRes['error'] ?? 'پرداخت ناموفق بود.'], 'main');
        }
    }

    protected function generateLicenseForOrder(array $order, array $product): void
    {
        $parts = [];
        for ($i = 0; $i < 4; $i++) {
            $parts[] = strtoupper(bin2hex(random_bytes(2)));
        }
        $licenseKey = 'EAFD-' . implode('-', $parts);

        $userId = $order['user_id'] ?? null;
        $licenseType = $product['license_type'] ?? 'lifetime';
        $durationDays = (int)($product['license_duration_days'] ?? 365);
        $maxActivations = (int)($product['max_activations'] ?? 1);

        $expiresAt = null;
        if ($licenseType === 'periodic') {
            $expiresAt = date('Y-m-d H:i:s', strtotime("+{$durationDays} days"));
        }

        $sql = "INSERT INTO licenses (license_key, user_id, product_id, order_id, license_type, max_activations, expires_at, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'active', CURRENT_TIMESTAMP)";
        Database::query($sql, [$licenseKey, $userId, $product['id'], $order['id'], $licenseType, $maxActivations, $expiresAt]);
    }
}
