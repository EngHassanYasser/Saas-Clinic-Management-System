<?php

namespace App\Http\Controllers;

use App\Http\Requests\doctor\StoreDoctorRequest;
use App\Http\Requests\doctor\UpdateDoctorRequest;
use App\Services\ClinicQueryService;
use App\Services\DoctorQueryService;
use App\services\DoctorService;
use App\Services\DoctorStatisticsService;
use App\Services\specialityQueryService;
use Illuminate\Support\Facades\Auth;

class DoctorController extends Controller
{
    public function __construct(
        private DoctorService $doctorService,
        private DoctorQueryService $doctorQueryService,
        private DoctorStatisticsService $doctorStatisticsService,
        private specialityQueryService $specialityQueryService,
        private ClinicQueryService $clinicQueryService,
    ) {}
    public function index()
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $doctors = $this->doctorQueryService->getAll($clinic->id);
        $specialities = $this->specialityQueryService->getAll();
        $stats = $this->doctorStatisticsService->getStats(Auth::user()->clinic_id);
        return view('doctors.index', compact('doctors', 'specialities', 'stats'));
    }
    public function store(StoreDoctorRequest $request, DoctorService $doctorService)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $doctorService->add($request->validated(), $clinic->id);

        return redirect()
            ->route('doctors.index')
            ->with('message', 'Doctor added successfully.');
    }
    public function update(UpdateDoctorRequest $request, int $doctorId)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $this->doctorService->update($request->validated(), $doctorId, $clinic->id);

        return redirect()
            ->route('doctors.index')
            ->with('message', 'تم تعديل بيانات الطبيب بنجاح');
    }
    public function destroy(int $doctorId)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $this->doctorService->deleteById($doctorId, $clinic->id);
        return redirect()->route('doctors.index')->with('message', 'doctor deleted successfully');
    }
}
