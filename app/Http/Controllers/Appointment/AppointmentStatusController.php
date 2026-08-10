<?php

namespace App\Http\Controllers\Appointment;

use App\Enums\EnAppointmentStatus;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Services\Appointment\AppointmentStatusService;

class AppointmentStatusController extends Controller
{
    public function __construct(
        private AppointmentStatusService $appointmentStatusService
    ) {}

    public function changeStatus(Appointment $appointment, EnAppointmentStatus $status)
    {
        $this->authorize('create', $appointment);
        $isUpdated = $this->appointmentStatusService->changeStatus($appointment, $status);
        $message = $isUpdated ? 'appointment '.$status->value.' successfully' : 'failed to update appointment status';

        return redirect()->route('appointments.index')->with('message', $message);
    }
}
