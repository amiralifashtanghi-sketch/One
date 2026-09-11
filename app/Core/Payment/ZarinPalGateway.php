<?php

namespace App\Core\Payment;

class ZarinPalGateway implements PaymentGatewayInterface
{
    protected string $merchantId;
    protected bool $isSandbox;

    public function __construct(string $merchantId = '', bool $isSandbox = true)
    {
        $this->merchantId = $merchantId ?: '00000000-0000-0000-0000-000000000000';
        $this->isSandbox = $isSandbox;
    }

    public function getId(): string
    {
        return 'zarinpal';
    }

    public function getName(): string
    {
        return 'درگاه پرداخت زرین‌پال (ZarinPal v4)';
    }

    protected function getApiUrl(string $endpoint): string
    {
        if ($this->isSandbox) {
            return 'https://sandbox.zarinpal.com/pg/v4/payment/' . $endpoint . '.json';
        }
        return 'https://api.zarinpal.com/pg/v4/payment/' . $endpoint . '.json';
    }

    protected function getStartPayUrl(string $authority): string
    {
        if ($this->isSandbox) {
            return 'https://sandbox.zarinpal.com/pg/StartPay/' . $authority;
        }
        return 'https://www.zarinpal.com/pg/StartPay/' . $authority;
    }

    public function request(array $orderData, string $callbackUrl): array
    {
        $url = $this->getApiUrl('request');
        // Convert Toman to Rial for ZarinPal API
        $amountRial = (int)$orderData['amount'] * 10;

        $payload = [
            'merchant_id' => $this->merchantId,
            'amount' => $amountRial,
            'callback_url' => $callbackUrl,
            'description' => 'خرید سفارش #' . $orderData['order_id'] . ' از ای‌اف‌دی',
            'metadata' => [
                'email' => $orderData['customer_email'] ?? '',
                'mobile' => $orderData['customer_phone'] ?? '',
            ]
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'EAFD Gateway Client');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $result = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return ['success' => false, 'error' => 'ارتباط با زرین‌پال برقرار نشد: ' . $err];
        }

        $res = json_decode($result, true);
        if (isset($res['data']['code']) && $res['data']['code'] == 100) {
            $authority = $res['data']['authority'];
            return [
                'success' => true,
                'transaction_id' => $authority,
                'redirect_url' => $this->getStartPayUrl($authority),
                'raw' => $res,
            ];
        }

        $msg = $res['errors']['message'] ?? ($res['errors']['code'] ?? 'خطای ناشناخته در درگاه زرین‌پال');
        return ['success' => false, 'error' => $msg];
    }

    public function verify(array $requestData, array $transactionData): array
    {
        $authority = $requestData['Authority'] ?? '';
        $status = $requestData['Status'] ?? '';

        if ($status !== 'OK') {
            return ['success' => false, 'error' => 'پرداخت توسط کاربر لغو شد یا ناموفق بود.'];
        }

        $url = $this->getApiUrl('verify');
        // Amount in Rial
        $amountRial = (int)$transactionData['amount'] * 10;

        $payload = [
            'merchant_id' => $this->merchantId,
            'amount' => $amountRial,
            'authority' => $authority,
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_USERAGENT, 'EAFD Gateway Client');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        $result = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($result, true);
        if (isset($res['data']['code']) && ($res['data']['code'] == 100 || $res['data']['code'] == 101)) {
            return [
                'success' => true,
                'reference_id' => (string)$res['data']['ref_id'],
                'amount' => $transactionData['amount'],
                'message' => 'پرداخت با موفقیت تایید گردید.',
            ];
        }

        $msg = $res['errors']['message'] ?? 'اعتبارسنجی تراکنش در سرور زرین‌پال ناموفق بود.';
        return ['success' => false, 'error' => $msg];
    }
}
