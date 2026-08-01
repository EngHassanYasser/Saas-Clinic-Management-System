<?php

namespace App\Http\Controllers;

use App\services\AppointmentAvailabilityService;
use Illuminate\Http\Request;

class AppointmentAvailabilityController extends Controller
{
    public function __construct(
        private AppointmentAvailabilityService $appointmentAvailabilityService
    ) {}
    public function getAvailableAppointments(Request $request, int $clinicId, int $doctorId, string $date)
    {
        return response()->json(
            $this->appointmentAvailabilityService->getAvailableAppointments($clinicId, $doctorId, $date)
        );
    }
}
