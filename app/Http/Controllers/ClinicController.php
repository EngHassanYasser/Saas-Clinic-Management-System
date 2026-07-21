<?php

namespace App\Http\Controllers;

use App\Http\Requests\Clinic\StoreClinicRequest;
use App\Http\Requests\Clinic\UpdateClinicRequest;
use App\Models\city;
use App\Models\clinic;
use App\Models\plan;
use App\services\ClinicService;

class ClinicController extends Controller
{
    public function __construct(private ClinicService $clinicService) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stats=$this->clinicService->getStats();
        $clinics = $this->clinicService->getAll();
        $cities = city::get(['id', 'name']);
        $plans = plan::get(['id', 'name']);
        return view('clinics.index', compact('clinics', 'cities', 'plans','stats'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreClinicRequest $request)
    {
        $this->clinicService->add($request->validated());
        return redirect()->route('clinics.index')->with('message', 'clinic added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

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
    public function update(UpdateClinicRequest $request, clinic $clinic)
    {
        $this->clinicService->update($request->validated(), $clinic);
        return redirect()->route('clinics.index')->with('message', 'clinic updated successfully');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Clinic $clinic)
    {
        $isDeleted = $this->clinicService->delete($clinic);
        $message = $isDeleted ? 'clinic deleted duccessfully' : 'failed to delete clinic';
        return redirect()->route('clinics.index')->with('message', $message);
    }
}
