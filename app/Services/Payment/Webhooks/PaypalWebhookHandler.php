<?php

namespace App\Services\Payment\Webhooks;

use App\Services\Payment\PaymentService;

class PayPalWebhookHandler
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    public function handle(
        array $payload,
        array $headers
    ): void {

        // Verify PayPal webhook signature here.

        $eventType = $payload['event_type'] ?? null;

        match ($eventType) {

            'PAYMENT.CAPTURE.COMPLETED'
                => $this->paymentSucceeded($payload),

            'PAYMENT.CAPTURE.DENIED'
                => $this->paymentFailed($payload),

            default
                => null,
        };
    }

    private function paymentSucceeded(array $payload): void
    {
        $captureId = $payload['resource']['id'] ?? null;

        if (!$captureId) {
            return;
        }

        $this->paymentService->handleSuccessfulPayment(
            gateway: 'paypal',
            gatewayPaymentId: $captureId,
        );
    }

    private function paymentFailed(array $payload): void
    {
        $captureId = $payload['resource']['id'] ?? null;

        if (!$captureId) {
            return;
        }

        $this->paymentService->handleFailedPayment(
            gateway: 'paypal',
            gatewayPaymentId: $captureId,
        );
    }
}