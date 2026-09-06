<?php

namespace App\Core\Payment;

class ZarinPalGateway implements PaymentGatewayInterface
{
    protected string $merchantId;

    public function __construct(string $merchantId = '00000000-0000-0000-0000-000000000000')
    {
        $this->merchantId = $merchantId;
    }

    public function requestPayment(array $order): array
    {
        // ZarinPal API Payment Request Structure
        $callbackUrl = "http://" . ($_SERVER['HTTP_HOST'] ?? 'localhost') . "/checkout/callback?gateway=zarinpal&order_id={$order['id']}";

        return [
            'success' => true,
            'redirect_url' => $callbackUrl . "&transaction_id=ZP-MOCK-" . time() . "&status=success",
            'transaction_id' => "ZP-MOCK-" . time(),
        ];
    }

    public function verifyPayment(array $requestData): array
    {
        $status = $requestData['status'] ?? '';
        if ($status === 'success' || $status === 'OK') {
            return [
                'success' => true,
                'transaction_id' => $requestData['transaction_id'] ?? ('ZP-' . time()),
                'message' => 'پرداخت زرین‌پال تایید شد.',
            ];
        }

        return [
            'success' => false,
            'transaction_id' => '',
            'message' => 'تایید پرداخت زرین‌پال تراکنش ناموفق اعلام کرد.',
        ];
    }
}
