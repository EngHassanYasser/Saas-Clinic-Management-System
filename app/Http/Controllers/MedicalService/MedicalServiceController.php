<?php

namespace App\Http\Controllers\MedicalService;

use App\DTOs\Services\Medical_Service\StoreMedicalrviceDTO;
use App\DTOs\Services\Medical_Service\UpdateMedical_ServiceDTO;
use App\Enums\EnRoleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Medical_Service\StoreMedical_ServiceRequest;
use App\Http\Requests\Medical_Service\UpdateMedical_ServiceRequest;
use App\Models\Medical_service;
use App\Services\Clinic\ClinicQueryService;
use App\Services\Doctor\DoctorQueryService;
use App\Services\MedicalService\MedicalServiceQueryService;
use App\Services\MedicalService\MedicalServiceService;
use App\Support\TenantContext;
use Illuminate\Support\Facades\Auth;

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


        $clinicId = $this->clinicQueryService->getClinicByOwnereId(Auth::id())->id;
        $medicalService = $this->medicalServiceQueryService->getAll();
        $doctors = $this->doctorQueryService->getDoctorsNames($clinicId);
        $clinicServices = $this->medicalServiceService->getAllDoctorServices();

        return view('MedicalServices.index', compact('medicalService', 'doctors', 'clinicServices'));
    }

    public function store(StoreMedical_ServiceRequest $request)
    {
        $this->authorize('create', Medical_service::class);

        $dto = StoreMedicalrviceDTO::fromRequest($request->validated());
        $clinicId = $this->tenantContext->id();
        $this->medicalServiceService->add($dto, $clinicId);

        return back()->with('success', 'تم إضافة الخدمة بنجاح');
    }

    public function update(UpdateMedical_ServiceRequest $request, Medical_service $medicalService)
    {
        $this->authorize('update', $medicalService);

        $dto = UpdateMedical_ServiceDTO::fromRequest($request);

        $clinicId = $this->tenantContext->id();

        $this->medicalServiceService->update($dto, $clinicId);

        return back()->with('success', 'تم تحديث الخدمة بنجاح');
    }

    public function destroy(int $medicalServiceId)
    {
        $this->authorize('delete', Medical_service::class);

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
