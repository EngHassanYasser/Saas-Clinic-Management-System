<?php

namespace App\Services\Payment;

use App\Services\Payment\Contracts\PaymentGatewayInterface;
use App\Services\Payment\Gateways\PayPalGateway;
use App\Services\Payment\Gateways\StripeGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    public function gateway(
        string $gateway
    ): PaymentGatewayInterface {

        return match ($gateway) {

            'stripe' => app(StripeGateway::class),

            'paypal' => app(PayPalGateway::class),

            default => throw new InvalidArgumentException(
                "Unsupported payment gateway: {$gateway}"
            ),
        };
    }
}