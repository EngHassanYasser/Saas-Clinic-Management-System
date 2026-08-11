<?php

namespace App\Services\Payment\Webhooks;

use App\Models\Payment;
use App\Services\Payment\PaymentService;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookHandler
{
    public function __construct(
        private readonly PaymentService $paymentService
    ) {}

    public function handle(
        string $payload,
        ?string $signature
    ): void {

        if (! $signature) {
            abort(400, 'Missing Stripe signature.');
        }

        try {
            $event = Webhook::constructEvent(
                $payload,
                $signature,
                config('services.stripe.webhook_secret')
            );
        } catch (UnexpectedValueException) {
            abort(400, 'Invalid payload.');
        } catch (SignatureVerificationException) {
            abort(400, 'Invalid signature.');
        }

        match ($event->type) {

            'payment_intent.succeeded' => $this->paymentSucceeded($event),

            'payment_intent.payment_failed' => $this->paymentFailed($event),

            default => null,
        };
    }

    private function paymentSucceeded($event): void
    {
        $intent = $event->data->object;

        $this->paymentService->handleSuccessfulPayment(
            gateway: 'stripe',
            gatewayPaymentId: $intent->id,
        );
    }

    private function paymentFailed($event): void
    {
        $intent = $event->data->object;

        Payment::query()
            ->where('gateway', 'stripe')
            ->where('gateway_payment_id', $intent->id)
            ->update([
                'status' => 'failed',
            ]);
    }
}
