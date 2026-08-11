<?php

namespace App\Services\Payment;

use App\DTOs\Payment\PaymentData;
use App\Models\Appointment;
use Illuminate\Support\Str;

class PaymentService
{
    public function __construct(
        private readonly PaymentGatewayManager $gatewayManager
    ) {}
    public function handleSuccessfulPayment(
    string $gateway,
    string $gatewayPaymentId
): void {

    DB::transaction(function () use (
        $gateway,
        $gatewayPaymentId
    ) {

        $payment = Payment::query()
            ->where('gateway', $gateway)
            ->where('gateway_payment_id', $gatewayPaymentId)
            ->lockForUpdate()
            ->first();

        if (!$payment || $payment->status === 'paid') {
            return;
        }

        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        $this->confirmAppointment($payment);
    });
}

 public function createForAppointment(
    Appointment $appointment,
    string $gateway,
    string $paymentMethod
) {
    $data = new PaymentData(
        amount: $appointment->deposit_amount,
        currency: 'EGP',
        description: "Appointment #{$appointment->id}",
        idempotencyKey: Str::uuid()->toString(),
        paymentMethod: $paymentMethod,
        metadata: [
            'appointment_id' => $appointment->id,
        ],
    );

    return $this->gatewayManager
        ->gateway($gateway)
        ->createPayment($data);
}
}