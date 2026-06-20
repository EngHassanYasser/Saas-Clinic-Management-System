<?php

namespace App\Http\Controllers;

use App\Http\Requests\clinicService\StoreClinicServiceRequest;
use App\services\ServiceCatalogService;

class ClinicServiceController extends Controller
{
    public function index()
    {
        $clinicServices  = [
            (object)[
                'service' => (object)[
                    'name' => 'كشف عام',
                    'description' => 'كشف شامل على المريض'
                ],
                'doctor' => (object)[
                    'name' => 'دكتور أحمد'
                ],
                'price' => 200
            ],
            (object)[
                'service' => (object)[
                    'name' => 'متابعة',
                    'description' => 'متابعة حالة المريض'
                ],
                'doctor' => (object)[
                    'name' => 'دكتورة سارة'
                ],
                'price' => 150
            ],
            (object)[
                'service' => (object)[
                    'name' => 'أشعة',
                    'description' => 'أشعة تشخيصية'
                ],
                'doctor' => (object)[
                    'name' => 'دكتور محمد'
                ],
                'price' => 300
            ]
        ];

        return view('Services.index', compact('clinicServices'));
    }
    public function store(StoreClinicServiceRequest $request, ServiceCatalogService $serviceCatalogService)
    {
        $data = $request->validated();
        $serviceCatalogService->addNew($data);
        return back()->with('success', 'تم إضافة الخدمة بنجاح');
    }
}
