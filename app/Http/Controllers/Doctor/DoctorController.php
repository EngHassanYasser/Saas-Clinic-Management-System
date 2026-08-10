<?php

namespace App\Http\Controllers\Doctor;

use App\DTOs\Services\Doctor\StoreDoctorDTO;
use App\DTOs\Services\Doctor\UpdateDoctorDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\doctor\StoreDoctorRequest;
use App\Http\Requests\doctor\UpdateDoctorRequest;
use App\Models\Doctor;
use App\Services\Doctor\DoctorQueryService;
use App\services\Doctor\DoctorService;
use App\Services\Doctor\DoctorStatisticsService;
use App\Services\Speciality\SpecialityQueryService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Concurrency;

class DoctorController extends Controller
{
    public function __construct(
        private DoctorService $doctorService,
        private DoctorQueryService $doctorQueryService,
        private DoctorStatisticsService $doctorStatisticsService,
        private SpecialityQueryService $specialityQueryService,
        private TenantContext $tenantContext,
    ) {}

    public function index()
    {
        $this->authorize('viewAny',Doctor::class);
        
        $clinicId = $this->tenantContext->id();

        [$doctors,$specialities,$stats] = Concurrency::run([
            fn () => $this->doctorQueryService->getAll($clinicId),
            fn () => $this->specialityQueryService->getAll(),
            fn () => $this->doctorStatisticsService->getStats($clinicId),
        ]);

        return view('doctors.index', compact('doctors', 'specialities', 'stats'));
    }

    public function store(StoreDoctorRequest $request)
    {
        $this->authorize('create',Doctor::class);
        
        $dto=StoreDoctorDTO::fromRequest($request->validated());
        $clinicId = $this->tenantContext->id();

        $this->doctorService->add($dto, $clinicId);

        return redirect()
            ->route('doctors.index')
            ->with('message', 'Doctor added successfully.');
    }

    public function update(UpdateDoctorRequest $request, Doctor $doctor)
    {
        $this->authorize('update',$doctor);

        $clinicId = $this->tenantContext->id();
        $dto=UpdateDoctorDTO::fromRequest($request->validated());
        $this->doctorService->update($dto, $doctor, $clinicId);

        return redirect()
            ->route('doctors.index')
            ->with('message', 'تم تعديل بيانات الطبيب بنجاح');
    }

    public function destroy(Doctor $doctor,int $clinicId)
    {
        $this->authorize('delete',$doctor);

        $this->doctorService->delete($doctor,$clinicId);

        return redirect()->route('doctors.index')->with('message', 'doctor deleted successfully');
    }
}
