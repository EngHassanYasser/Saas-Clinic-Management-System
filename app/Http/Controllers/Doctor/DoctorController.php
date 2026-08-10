<?php

namespace App\Http\Controllers\Doctor;

use App\DTOs\Services\Doctor\StoreDoctorDTO;
use App\DTOs\Services\Doctor\UpdateDoctorDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\doctor\StoreDoctorRequest;
use App\Http\Requests\doctor\UpdateDoctorRequest;
use App\Services\Doctor\DoctorQueryService;
use App\services\Doctor\DoctorService;
use App\Services\Doctor\DoctorStatisticsService;
use App\Services\Speciality\SpecialityQueryService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Concurrency;

class DoctorController extends Controller
{
    public function __construct(
        private DoctorService $medicalService,
        private DoctorQueryService $doctorQueryService,
        private DoctorStatisticsService $doctorStatisticsService,
        private SpecialityQueryService $specialityQueryService,
        private TenantContext $tenantContext,
    ) {}

    public function index()
    {
        $clinicId = $this->tenantContext->id();

        [$doctors,$specialities,$stats] = Concurrency::run([
            fn () => $this->doctorQueryService->getAll($clinicId),
            fn () => $this->specialityQueryService->getAll(),
            fn () => $this->doctorStatisticsService->getStats($clinicId),
        ]);

        return view('doctors.index', compact('doctors', 'specialities', 'stats'));
    }

    public function store(StoreDoctorRequest $request, DoctorService $medicalService)
    {
        $dto=StoreDoctorDTO::fromRequest($request->validated());
        $clinicId = $this->tenantContext->id();

        $medicalService->add($dto, $clinicId);

        return redirect()
            ->route('doctors.index')
            ->with('message', 'Doctor added successfully.');
    }

    public function update(UpdateDoctorRequest $request, int $doctorId)
    {
        $clinicId = $this->tenantContext->id();
        $dto=UpdateDoctorDTO::fromRequest($request->validated());
        $this->medicalService->update($dto, $doctorId, $clinicId);

        return redirect()
            ->route('doctors.index')
            ->with('message', 'تم تعديل بيانات الطبيب بنجاح');
    }

    public function destroy(int $doctorId)
    {
        $clinicId = $this->tenantContext->id();
        $this->medicalService->deleteById($doctorId, $clinicId);

        return redirect()->route('doctors.index')->with('message', 'doctor deleted successfully');
    }
}
