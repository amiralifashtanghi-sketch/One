<?php

namespace App\Core\Payment;

interface PaymentGatewayInterface
{
    public function getId(): string;
    public function getName(): string;
    public function request(array $orderData, string $callbackUrl): array;
    public function verify(array $requestData, array $transactionData): array;
}
