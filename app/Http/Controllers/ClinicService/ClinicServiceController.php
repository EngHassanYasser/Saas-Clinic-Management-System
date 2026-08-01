<?php

namespace App\Http\Controllers;

use App\Http\Requests\clinicService\StoreClinicServiceRequest;
use App\Http\Requests\clinicService\UpdateClinicServiceRequest;
use App\Services\ClinicQueryService;
use App\Services\ClinicServicePriceService;
use App\Services\DoctorQueryService;
use App\services\DoctorService;
use App\services\ServiceCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicServiceController extends Controller
{

    public function __construct(
        private ServiceCatalogService $serviceCatalogService,
        private DoctorService $doctorService,
        private DoctorQueryService $doctorQueryService,
        private ClinicServicePriceService $clinicServicePriceService,
        private ClinicQueryService $clinicQueryService,
    ) {
        $this->serviceCatalogService = $serviceCatalogService;
        $this->doctorService = $doctorService;
    }
    public function index()
    {
        $serviceCatalogs = $this->serviceCatalogService->getAllCatalogs();
        $doctors = $this->doctorQueryService->getDoctorsNames(Auth::user()->clinic_id);
        $clinicServices = $this->clinicServicePriceService->getAllClinicServices();
        return view('Services.index', compact('serviceCatalogs', 'doctors', 'clinicServices'));
    }
    public function store(StoreClinicServiceRequest $request)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());

        $this->clinicServicePriceService->add($request->validated(), $clinic->id);

        return back()->with('success', 'تم إضافة الخدمة بنجاح');
    }
    public function update(UpdateClinicServiceRequest $request)
    {
        $clinic = $this->clinicQueryService->getClinicByOwnereId(Auth::id());
        $this->clinicServicePriceService->update($request->validated(), $clinic->id);

        return back()->with('success', 'تم تحديث الخدمة بنجاح');
    }
    public function destroy(Request $request)
    {
        $isDeleted = $this->clinicServicePriceService->deleteById($request->id);

        return response()->json([
            'success' => $isDeleted > 0,
            'message' => $isDeleted > 0
                ? 'Service deleted successfully'
                : 'Service not found'
        ]);
    }
}
