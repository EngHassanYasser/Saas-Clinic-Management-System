<?php

namespace App\Services\Payment\Gateways;

use App\DTOs\Payment\PaymentData;
use App\DTOs\Payment\PaymentResult;
use App\Services\Payment\Contracts\PaymentGatewayInterface;

class PayPalGateway implements PaymentGatewayInterface
{
    public function createPayment(PaymentData $data): PaymentResult
    {
        // Create PayPal Order

        // Example conceptual result:

        return new PaymentResult(
            paymentId: 'PAYPAL_ORDER_ID',
            status: 'created',
            checkoutUrl: 'PAYPAL_APPROVAL_URL',
        );
    }

    public function verifyPayment(string $paymentId): PaymentResult
    {
        // Get PayPal order/payment details

        return new PaymentResult(
            paymentId: $paymentId,
            status: 'completed',
        );
    }

    public function refund(
        string $paymentId,
        ?float $amount = null
    ): bool {

        // PayPal capture refund

        return true;
    }
}