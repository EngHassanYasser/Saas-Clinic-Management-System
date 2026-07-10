<?php

namespace App\Http\Controllers;

use App\services\AppointmentService;
use App\services\ServiceCatalogService;
use App\services\SpecialityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct(private AppointmentService $appointmentService,
    private SpecialityService $specialityService,
    private ServiceCatalogService $serviceCatalogService,
    ){

    }
    public function index()
    {
        $appointments= $this->appointmentService->getAppointments(Auth::User());
        $stats = $this->appointmentService->getStats(Auth::user());
        return view('appointments.index',compact('appointments','stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
        $specialities = $this->specialityService->getAll();
        $services = $this->serviceCatalogService->getAllCatalogs();
        return view('appointments.create',compact('specialities','services'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
