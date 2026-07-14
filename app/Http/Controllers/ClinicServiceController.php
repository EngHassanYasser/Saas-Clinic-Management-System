<?php

namespace App\Http\Controllers;

use App\Http\Requests\clinicService\StoreClinicServiceRequest;
use App\Http\Requests\clinicService\UpdateClinicServiceRequest;
use App\services\DoctorService;
use App\services\ServiceCatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicServiceController extends Controller
{
    private ServiceCatalogService $serviceCatalogService;
    private DoctorService $doctorService;

    public function __construct(
        ServiceCatalogService $serviceCatalogService,
        DoctorService $doctorService
    ) {
        $this->serviceCatalogService = $serviceCatalogService;
        $this->doctorService = $doctorService;
    }
    public function index()
    {
        $serviceCatalogs = $this->serviceCatalogService->getAllCatalogs();
        $doctors = $this->doctorService->getDoctorsNames(Auth::user()->clinic_id);
        $clinicServices = $this->serviceCatalogService->getAllClinicServices();
        return view('Services.index', compact('serviceCatalogs', 'doctors', 'clinicServices'));
    }
    public function store(StoreClinicServiceRequest $request)
    {
        $this->serviceCatalogService->addNew($request->validated());

        return back()->with('success', 'تم إضافة الخدمة بنجاح');
    }
    public function update(UpdateClinicServiceRequest $request)
    {
        $this->serviceCatalogService->update($request->validated());

        return back()->with('success', 'تم تحديث الخدمة بنجاح');
    }
    public function destroy(Request $request)
    {
        $isDeleted = $this->serviceCatalogService->deleteById($request->id);

        return response()->json([
            'success' => $isDeleted > 0,
            'message' => $isDeleted > 0
                ? 'Service deleted successfully'
                : 'Service not found'
        ]);
    }
}
