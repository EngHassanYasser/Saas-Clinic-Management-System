<?php

namespace App\Http\Controllers\Appointment;

use App\DTOs\Services\Appointment\RescheduleDTO;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\services\Appointment\AppointmentService;

class AppointmentRescheduleController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService,
    ) {}

    public function reschdule(Appointment $appointment, string $visiteDate, string $startTime)
    {
        $this->authorize('reschedule', $appointment);
        
        $dto = new RescheduleDTO($appointment,$visiteDate,$startTime);
        $isRescheduled = $this->appointmentService->reschedule($dto);

        $message = $isRescheduled ? 'rescheduled done successfully' : 'failed to reschedule appointment please try again';

        return redirect()->route('appointments.index')->with('message', $message);
    }
}
