<?php

namespace App\Core\Payment;

class MockGateway implements PaymentGatewayInterface
{
    public function requestPayment(array $order): array
    {
        $mockTransactionId = 'MOCK-TXN-' . time() . '-' . rand(1000, 9999);
        return [
            'success' => true,
            'redirect_url' => "/checkout/callback?gateway=mock&order_id={$order['id']}&transaction_id={$mockTransactionId}&status=success",
            'transaction_id' => $mockTransactionId,
        ];
    }

    public function verifyPayment(array $requestData): array
    {
        $status = $requestData['status'] ?? 'failed';
        $transactionId = $requestData['transaction_id'] ?? ('MOCK-' . time());

        if ($status === 'success') {
            return [
                'success' => true,
                'transaction_id' => $transactionId,
                'message' => 'پرداخت آزمایشی درگاه Sandbox با موفقیت انجام گردید.',
            ];
        }

        return [
            'success' => false,
            'transaction_id' => $transactionId,
            'message' => 'پرداخت آزمایشی ناموفق بود.',
        ];
    }
}
