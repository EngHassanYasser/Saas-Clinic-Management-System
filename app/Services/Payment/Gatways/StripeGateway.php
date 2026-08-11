<?php

namespace App\Services\Payment\Gateways;

use App\DTOs\Payment\PaymentData;
use App\DTOs\Payment\PaymentResult;
use App\Services\Payment\Contracts\PaymentGatewayInterface;
use Stripe\StripeClient;

class StripeGateway implements PaymentGatewayInterface
{
    public function __construct(
        private readonly StripeClient $stripe
    ) {}

    public function createPayment(PaymentData $data): PaymentResult
    {
        $intent = $this->stripe->paymentIntents->create(
            [
                'amount' => $data->amount,
                'currency' => strtolower($data->currency),

                'description' => $data->description,

                'metadata' => $data->metadata,

                'automatic_payment_methods' => [
                    'enabled' => true,
                ],
            ],
            [
                'idempotency_key' => $data->idempotencyKey,
            ]
        );

        return new PaymentResult(
            paymentId: $intent->id,
            status: $intent->status,
            raw: $intent->toArray(),
        );
    }

    public function verifyPayment(string $paymentId): PaymentResult
    {
        $intent = $this->stripe
            ->paymentIntents
            ->retrieve($paymentId);

        return new PaymentResult(
            paymentId: $intent->id,
            status: $intent->status,
            raw: $intent->toArray(),
        );
    }

    public function refund(
        string $paymentId,
        ?float $amount = null
    ): bool {

        $data = [
            'payment_intent' => $paymentId,
        ];

        if ($amount !== null) {
            $data['amount'] = (int) $amount;
        }

        $refund = $this->stripe->refunds->create($data);

        return $refund->status === 'succeeded';
    }
}