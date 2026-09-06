<?php

namespace App\Core\Payment;

interface PaymentGatewayInterface
{
    public function requestPayment(array $order): array;
    public function verifyPayment(array $requestData): array;
}
