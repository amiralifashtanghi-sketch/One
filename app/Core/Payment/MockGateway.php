<?php

namespace App\Core\Payment;

class MockGateway implements PaymentGatewayInterface
{
    public function getId(): string
    {
        return 'mock';
    }

    public function getName(): string
    {
        return 'درگاه پرداخت آزمایشی (Mock)';
    }

    public function request(array $orderData, string $callbackUrl): array
    {
        $authority = 'MOCK_' . uniqid() . '_' . rand(1000, 9999);
        $redirectUrl = $callbackUrl . (str_contains($callbackUrl, '?') ? '&' : '?') . 'Authority=' . $authority . '&Status=OK';

        return [
            'success' => true,
            'transaction_id' => $authority,
            'redirect_url' => $redirectUrl,
            'raw' => ['status' => 'initiated'],
        ];
    }

    public function verify(array $requestData, array $transactionData): array
    {
        $status = $requestData['Status'] ?? $requestData['status'] ?? 'OK';
        if ($status === 'OK' || $status === '100') {
            return [
                'success' => true,
                'reference_id' => 'REF_' . rand(100000, 999999),
                'amount' => $transactionData['amount'],
                'message' => 'پرداخت آزمایشی با موفقیت انجام شد.',
            ];
        }

        return [
            'success' => false,
            'error' => 'پرداخت آزمایشی توسط کاربر لغو شد.',
        ];
    }
}
