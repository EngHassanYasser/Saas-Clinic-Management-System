<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\Models\User;
use App\Services\AppointmentStatusService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class AppointmentStatusController extends Controller
{
    public function __construct(
        private AppointmentStatusService $appointmentStatusService
    ) {}
    public function changeStatus(Request $request, int $appointmentId, User $user)
    {
        $request->validate([
            'status' => ['required', new Enum(AppointmentStatus::class)],
        ]);

        $status = AppointmentStatus::from($request->status);
        $isUpdated = $this->appointmentStatusService->changeStatus($status, $appointmentId, $user);
        $message = $isUpdated ? 'appointment ' . $request->status . ' successfully' : 'failed to update appointment status';

        return redirect()->route('appointments.index')->with('message', $message);
    }
    public function confirm() {}

    public function cancel() {}

    public function complete() {}

    public function reject() {}

    public function markAsNoShow() {}
}
