<?php

namespace App\Http\Controllers\DoctorService;

use App\DTOs\Services\MedicalService\StoreMedicalrviceDTO;
use App\DTOs\Services\MedicalService\UpdateMedicalServiceDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalService\StoreMedicalServiceRequest;
use App\Http\Requests\MedicalService\UpdateMedicalServiceRequest;
use App\Models\MedicalService as MedicalServiceModel;
use App\Services\Clinic\ClinicQueryService;
use App\Services\Doctor\DoctorQueryService;
use App\Services\MedicalService\MedicalServiceQueryService;
use App\Services\MedicalService\MedicalServiceService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Concurrency;

class MedicalServiceController extends Controller
{
    public function __construct(
        private MedicalServiceQueryService $medicalServiceQueryService,
        private DoctorQueryService $doctorQueryService,
        private MedicalServiceService $medicalServiceService,
        private ClinicQueryService $clinicQueryService,
        private TenantContext $tenantContext
    ) {}

    public function index()
    {
        $this->authorize('viewAny', MedicalServiceModel::class);

        $clinicId = $this->clinicQueryService->getClinicByOwnereId(Auth::id())->id;
        [$serviceCatalogs,$doctors,$clinicServices] = Concurrency::run([
            fn () => $this->medicalServiceQueryService->getAll(),
            fn () => $this->doctorQueryService->getDoctorsNames($clinicId),
            fn () => $this->medicalServiceService->getAllDoctorServices(),
        ]);

        return view('Services.index', compact('serviceCatalogs', 'doctors', 'clinicServices'));
    }

    public function store(StoreMedicalServiceRequest $request)
    {
        $this->authorize('create', MedicalServiceModel::class);
        
        $dto = StoreMedicalrviceDTO::fromRequest($request->validated());
        $clinicId = $this->tenantContext->id();
        $this->medicalServiceService->add($dto, $clinicId);

        return back()->with('success', 'تم إضافة الخدمة بنجاح');
    }

    public function update(UpdateMedicalServiceRequest $request, MedicalServiceModel $medicalService)
    {
        $this->authorize('update', $medicalService);

        $dto = UpdateMedicalServiceDTO::fromRequest($request);

        $clinicId = $this->tenantContext->id();

        $this->medicalServiceService->update($dto, $clinicId);

        return back()->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroy(int $medicalServiceId)
    {
        $this->authorize('delete', MedicalServiceModel::class);

        $clinicId = $this->tenantContext->id();

        $isDeleted = $this->medicalServiceService->deleteById($medicalServiceId, $clinicId);

        return response()->json([
            'success' => $isDeleted > 0,
            'message' => $isDeleted > 0
                ? 'Service deleted successfully'
                : 'Service not found',
        ]);
    }
}
