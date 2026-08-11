<?php

namespace App\Http\Controllers;

use App\Http\Requests\Payment\PaymentRequest;
use App\Models\Appointment;
use App\Services\Payment\PaymentService;
use Illuminate\Http\JsonResponse;

class PaymentController extends Controller
{
    public function pay(
        Appointment $appointment,
        PaymentService $paymentService,
        PaymentRequest $request
    ): JsonResponse {

        $result = $paymentService->createForAppointment(
            appointment: $appointment,
            gateway: $request->gateway,
            paymentMethod: $request->payment_method,
        );

        return response()->json($result);
    }
}
