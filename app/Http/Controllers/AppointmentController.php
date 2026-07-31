<?php

namespace App\Http\Controllers;

use App\Enums\AppointmentStatus;
use App\services\AppointmentService;
use App\services\ServiceCatalogService;
use App\services\SpecialityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\appointments\RescheduleAppointment;
use App\Http\Requests\appointments\StoreAppointmentRequest;
use Illuminate\Validation\Rules\Enum;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentService $appointmentService,
        private SpecialityService $specialityService,
        private ServiceCatalogService $serviceCatalogService,
    ) {}
    public function index()
    {
        $appointments = $this->appointmentService->getAppointments(Auth::User());
        $stats = $this->appointmentService->getStats(Auth::user());
        return view('appointments.index', compact('appointments', 'stats'));
    }
    public function create()
    {

        $specialities = $this->specialityService->getAll();
        $services = $this->serviceCatalogService->getAllCatalogs();
        return view('appointments.create', compact('specialities', 'services'));
    }
    public function changeStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => ['required', new Enum(AppointmentStatus::class)],
        ]);

        $status = AppointmentStatus::from($request->status);
        $isUpdated = $this->appointmentService->changeStatus($status, $id);
        $message = $isUpdated ? 'appointment '. $request->status .' successfully' : 'failed to update appointment status';

        return redirect()->route('appointments.index')->with('message', $message);
    }
    public function getAvailableAppointments(Request $request, int $clinicId, int $doctorId, string $date)
    {
        return response()->json(
            $this->appointmentService->getAvailableAppointments($clinicId, $doctorId, $date)
        );
    }
    public function reschdule(RescheduleAppointment $request)
    {
        $isRescheduled = $this->appointmentService->reschdule($request->validated());
        $message = $isRescheduled ? 'rescheduled done successfully' : 'failed to reschedule appointment please try again';
        return redirect()->route('appointments.index')->with('message', $message);
    }
    public function store(StoreAppointmentRequest $request)
    {
        $this->appointmentService->add($request->validated());
        return redirect()->route('appointments.index')
            ->with('message', 'appointment booked successfully please confirm appointment');
    }
}
