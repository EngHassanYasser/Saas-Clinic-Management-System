<?php

namespace App\Http\Controllers\Appointment;

use App\DTOs\Services\Appointment\GetAvailableAppointmentsDTO;
use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Doctor;
use App\services\Appointment\AppointmentAvailabilityService;

class AppointmentAvailabilityController extends Controller
{
    public function __construct(
        private AppointmentAvailabilityService $appointmentAvailabilityService
    ) {}
    public function getAvailableAppointments(Clinic $clinic, Doctor $doctor, string $visiteDate)
    {
        $dto = new GetAvailableAppointmentsDTO($clinic,$doctor,$visiteDate);
        return response()->json(
            $this->appointmentAvailabilityService->getAvailableAppointments($dto)
        );
    }
}
