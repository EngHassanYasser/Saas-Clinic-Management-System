<?php

namespace App\DTOs\Payment;

use App\Enums\EnPaymentMethod;

class PaymentData
{
    public function __construct(
        public readonly int $amount,
        public readonly string $currency,
        public readonly string $description,
        public readonly string $idempotencyKey,
        public readonly EnPaymentMethod $paymentMethod,
        public readonly ?string $returnUrl = null,
        public readonly ?string $cancelUrl = null,
        public readonly array $metadata = [],
    ) {}
}