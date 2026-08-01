<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\services\Appointment\AppointmentAvailabilityService;
use Illuminate\Http\Request;

class AppointmentAvailabilityController extends Controller
{
    public function __construct(
        private AppointmentAvailabilityService $appointmentAvailabilityService
    ) {}
    public function getAvailableAppointments(Request $request, int $clinicId, int $doctorId, string $vistDate)
    {
        return response()->json(
            $this->appointmentAvailabilityService->getAvailableAppointments($clinicId, $doctorId, $vistDate)
        );
    }
}
