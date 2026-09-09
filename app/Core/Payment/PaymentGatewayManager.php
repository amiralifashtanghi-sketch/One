<?php

namespace App\Core\Payment;

use App\Core\Config;

class PaymentGatewayManager
{
    protected array $gateways = [];

    public function __construct()
    {
        $this->registerGateway(new MockGateway());

        $merchant = Config::get('payment.zarinpal_merchant', '00000000-0000-0000-0000-000000000000');
        $sandbox = Config::get('payment.zarinpal_sandbox', true);
        $this->registerGateway(new ZarinPalGateway($merchant, (bool)$sandbox));
    }

    public function registerGateway(PaymentGatewayInterface $gateway): void
    {
        $this->gateways[$gateway->getId()] = $gateway;
    }

    public function getGateway(string $id): PaymentGatewayInterface
    {
        if (!isset($this->gateways[$id])) {
            return $this->gateways['mock'];
        }
        return $this->gateways[$id];
    }

    public function getAvailableGateways(): array
    {
        return $this->gateways;
    }
}
