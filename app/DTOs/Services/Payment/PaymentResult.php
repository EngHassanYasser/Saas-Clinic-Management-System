<?php

namespace App\DTOs\Payment;

class PaymentResult
{
    public function __construct(
        public readonly string $paymentId,
        public readonly string $status,
        public readonly ?string $checkoutUrl = null,
        public readonly array $raw = [],
    ) {}
}