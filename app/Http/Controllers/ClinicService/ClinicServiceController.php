<?php

namespace App\Http\Controllers\ClinicService;

use App\Http\Controllers\Controller;
use App\Http\Requests\clinicService\StoreClinicServiceRequest;
use App\Http\Requests\clinicService\UpdateClinicServiceRequest;
use App\Services\Clinic\ClinicQueryService;
use App\Services\Doctor\DoctorQueryService;
use App\services\Doctor\DoctorService;
use App\Services\ServiceCatalog\ClinicServicePriceService;
use App\services\ServiceCatalog\ServiceCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Concurrency;

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
        $clinicId = $this->clinicQueryService->getClinicByOwnereId(Auth::id())->id;
        [$serviceCatalogs,$doctors,$clinicsServices] = Concurrency::run([
            fn () => $this->serviceCatalogService->getAllCatalogs(),
            fn () => $this->doctorQueryService->getDoctorsNames($clinicId),
            fn () => $this->clinicServicePriceService->getAllClinicServices(),
        ]);

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
                : 'Service not found',
        ]);
    }
}
