<?php

namespace App\Http\Controllers;

use App\Http\Requests\appointments\RescheduleAppointment;
use App\services\AppointmentService;
use App\Services\ClinicQueryService;
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
