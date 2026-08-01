<?php

namespace App\Http\Controllers\Appointment;

use App\Http\Controllers\Controller;
use App\Http\Requests\appointments\StoreAppointmentRequest;
use App\Services\Appointment\AppointmentQueryService;
use App\services\Appointment\AppointmentService;
use App\Services\Speciality\SpecialityQueryService;
use App\services\Appointment\AppointmentStatisticsService;
use App\Services\ServiceCatalog\ServiceCatalogService;
use Illuminate\Support\Facades\Auth;

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
        $appointments = $this->appointmentQueryServie->getAppointments(Auth::User());
        $stats = $this->appointmentStatisticsService->getStats(Auth::user());
        return view('appointments.index', compact('appointments', 'stats'));
    }
    public function create()
    {

        $specialities = $this->specialityQueryService->getAll();
        $services = $this->serviceCatalogService->getAllCatalogs();
        return view('appointments.create', compact('specialities', 'services'));
    }
    public function store(StoreAppointmentRequest $request)
    {
        $this->appointmentService->add($request->validated(), Auth::id());
        return redirect()->route('appointments.index')
            ->with('message', 'appointment booked successfully please confirm appointment');
    }
}
