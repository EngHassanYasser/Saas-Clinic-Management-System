<?php

namespace App\Http\Controllers\Appointment;

use App\DTOs\Services\Appointment\GetAvailableAppointmentsDTO;
use App\Http\Controllers\Controller;
use App\services\Appointment\AppointmentAvailabilityService;

class AppointmentAvailabilityController extends Controller
{
    public function __construct(
        private AppointmentAvailabilityService $appointmentAvailabilityService
    ) {}
    public function getAvailableAppointments(int $clinicId, int $doctorId, string $visiteDate)
    {
        $dto = new GetAvailableAppointmentsDTO($clinicId,$doctorId,$visiteDate);
        return response()->json(
            $this->appointmentAvailabilityService->getAvailableAppointments($dto)
        );
    }
}
