<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\doctor\StoreDoctorRequest;
use App\Http\Requests\doctor\UpdateDoctorRequest;
use App\Services\Clinic\ClinicQueryService;
use App\Services\Doctor\DoctorQueryService;
use App\services\Doctor\DoctorService;
use App\Services\Doctor\DoctorStatisticsService;
use App\Services\Speciality\SpecialityQueryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Concurrency;

class DoctorController extends Controller
{
    public function __construct(
        private DoctorService $doctorService,
        private DoctorQueryService $doctorQueryService,
        private DoctorStatisticsService $doctorStatisticsService,
        private SpecialityQueryService $specialityQueryService,
        private ClinicQueryService $clinicQueryService,
    ) {}

    public function index()
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        [$doctors,$specialities,$stats] = Concurrency::run([
            fn () => $this->doctorQueryService->getAll($clinic->id),
            fn () => $this->specialityQueryService->getAll(),
            fn () => $this->doctorStatisticsService->getStats($clinic->id),
        ]);

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
