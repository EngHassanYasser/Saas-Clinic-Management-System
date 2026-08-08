<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\appointments\RescheduleAppointment;
use App\services\Appointment\AppointmentService;
use App\Support\TenantContext;

class AppointmentRescheduleController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService,
        private TenantContext $tenantContext,
    ) {}

    public function reschdule(RescheduleAppointment $request)
    {
        $clinicId = $this->tenantContext->id();
        $isRescheduled = $this->appointmentService->reschedule($request->validated(), $clinicId);
        $message = $isRescheduled ? 'rescheduled done successfully' : 'failed to reschedule appointment please try again';

        return redirect()->route('appointments.index')->with('message', $message);
    }
}
