<?php

namespace App\Http\Controllers;

use App\Http\Requests\Clinic\StoreClinicRequest;
use App\Http\Requests\Clinic\UpdateClinicRequest;
use App\Models\City;
use App\Models\Clinic;
use App\Models\Plan;
use App\services\ClinicService;

class ClinicController extends Controller
{
    public function __construct(private ClinicService $clinicService) {}
    public function index()
    {
        $stats=$this->clinicService->getStats();
        $clinics = $this->clinicService->getAll();
        $cities = City::get(['id', 'name']);
        $plans = Plan::get(['id', 'name']);
        return view('clinics.index', compact('clinics', 'cities', 'plans','stats'));
    }
    public function store(StoreClinicRequest $request)
    {
        $this->clinicService->add($request->validated());
        return redirect()->route('clinics.index')->with('message', 'clinic added successfully');
    }
    public function update(UpdateClinicRequest $request, clinic $clinic)
    {
        $this->clinicService->update($request->validated(), $clinic);
        return redirect()->route('clinics.index')->with('message', 'clinic updated successfully');
    }
    public function destroy(Clinic $clinic)
    {
        $isDeleted = $this->clinicService->delete($clinic);
        $message = $isDeleted ? 'clinic deleted duccessfully' : 'failed to delete clinic';
        return redirect()->route('clinics.index')->with('message', $message);
    }
}
