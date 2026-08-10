<?php

namespace App\Http\Controllers\Appointment;

use App\DTOs\Services\Appointment\StoreAppointmentDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\appointments\StoreAppointmentRequest;
use App\Models\Appointment;
use App\Services\Appointment\AppointmentQueryService;
use App\services\Appointment\AppointmentService;
use App\services\Appointment\AppointmentStatisticsService;
use App\Services\MedicalService\MedicalServiceQueryService;
use App\Services\Speciality\SpecialityQueryService;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentQueryService $appointmentQueryServie,
        private AppointmentStatisticsService $appointmentStatisticsService,
        private SpecialityQueryService $specialityQueryService,
        private MedicalServiceQueryService $medicalServiceQueryService,
        private AppointmentService $appointmentService,
    ) {}

    public function index()
    {
        $this->authorize('viewAny', Appointment::class);

        $appointments = $this->appointmentQueryServie->getAppointments(Auth::User());
        $stats = $this->appointmentStatisticsService->getStats(Auth::user());

        return view('appointments.index', compact('appointments', 'stats'));
    }

    public function create()
    {
        $this->authorize('create', Appointment::class);

        $specialities = $this->specialityQueryService->getAll();
        $services = $this->medicalServiceQueryService->getAll();

        return view('appointments.create', compact('specialities', 'services'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $this->authorize('create', Appointment::class);
        $dto = StoreAppointmentDTO::fromRequest($request->validated());
        $this->appointmentService->add($dto, Auth::id());

        return redirect()->route('appointments.index')
            ->with('message', 'appointment booked successfully please confirm appointment');
    }
}
