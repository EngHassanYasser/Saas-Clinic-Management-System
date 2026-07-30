<?php

namespace App\Http\Controllers;

use App\Http\Requests\doctor\StoreDoctorRequest;
use App\Http\Requests\doctor\UpdateDoctorRequest;
use App\services\DoctorService;
use App\services\SpecialityService;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function __construct(
        private DoctorService $doctorService,
        private SpecialityService $specialityService
    ) {}
    public function index()
    {
        $doctors = $this->doctorService->getAll();
        $specialities = $this->specialityService->getAll();
        $stats = $this->doctorService->getStats(Auth::user()->clinic_id);
        return view('doctors.index', compact('doctors', 'specialities', 'stats'));
    }
    public function store(StoreDoctorRequest $request, DoctorService $doctorService)
    {
        $doctorService->addNew($request->validated());

        return redirect()
            ->route('doctors.index')
            ->with('message', 'Doctor added successfully.');
    }
    public function update(UpdateDoctorRequest $request, int $id)
    {
        $this->doctorService->update($request->validated(), $id);

        return redirect()
            ->route('doctors.index')
            ->with('message', 'تم تعديل بيانات الطبيب بنجاح');
    }
    public function destroy(int $id)
    {
        $this->doctorService->deleteById($id);
        return redirect()->route('doctors.index')->with('message', 'doctor deleted successfully');
    }
    public function getAvailableDoctors(int $clinicId, int $specialityId, int $serviceId)
    {
        return response()->json([
            'success' => true,
            'data' => $this->doctorService->getAvailableDoctors($clinicId, $specialityId, $serviceId),
        ]);
    }
}
