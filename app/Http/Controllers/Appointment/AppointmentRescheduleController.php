<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\appointments\RescheduleAppointment;
use App\services\Appointment\AppointmentService;
use App\Services\Clinic\ClinicQueryService;
use Illuminate\Support\Facades\Auth;

class AppointmentRescheduleController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService,
        private ClinicQueryService $clinicQueryService
    ) {}
    public function reschdule(RescheduleAppointment $request)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $isRescheduled = $this->appointmentService->reschedule($request->validated(), $clinic->id);
        $message = $isRescheduled ? 'rescheduled done successfully' : 'failed to reschedule appointment please try again';
        return redirect()->route('appointments.index')->with('message', $message);
    }
}
