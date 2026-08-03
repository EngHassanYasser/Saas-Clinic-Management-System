<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\appointments\StoreAppointmentRequest;
use App\Services\Appointment\AppointmentQueryService;
use App\services\Appointment\AppointmentService;
use App\services\Appointment\AppointmentStatisticsService;
use App\Services\ServiceCatalog\ServiceCatalogService;
use App\Services\Speciality\SpecialityQueryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Concurrency;

class AppointmentController extends Controller
{
    public function __construct(
        private AppointmentQueryService $appointmentQueryServie,
        private AppointmentStatisticsService $appointmentStatisticsService,
        private SpecialityQueryService $specialityQueryService,
        private ServiceCatalogService $serviceCatalogService,
        private AppointmentService $appointmentService,
    ) {}

    public function index()
    {
        [$appointments,$stats] = Concurrency::run([
            fn () => $this->appointmentQueryServie->getAppointments(Auth::User()),
            fn () => $this->appointmentStatisticsService->getStats(Auth::user()),
        ]);

        return view('appointments.index', compact('appointments', 'stats'));
    }

    public function create()
    {
        [$specialities,$services] = Concurrency::run([
            fn () => $this->specialityQueryService->getAll(),
            fn () => $this->serviceCatalogService->getAllCatalogs(),
        ]);

        return view('appointments.create', compact('specialities', 'services'));
    }

    public function store(StoreAppointmentRequest $request)
    {
        $this->appointmentService->add($request->validated(), Auth::id());

        return redirect()->route('appointments.index')
            ->with('message', 'appointment booked successfully please confirm appointment');
    }
}
