<?php

namespace App\Services\Payment\Contracts;

use App\DTOs\Payment\PaymentData;
use App\DTOs\Payment\PaymentResult;

interface PaymentGatewayInterface
{
    public function createPayment(PaymentData $data): PaymentResult;

    public function verifyPayment(string $paymentId): PaymentResult;

    public function refund(
        string $paymentId,
        ?float $amount = null
    ): bool;
}