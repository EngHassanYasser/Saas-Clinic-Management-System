<?php

namespace App\http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Services\Clinic\ClinicQueryService;

class ClinicLookupController extends Controller
{
    public function __construct(private ClinicQueryService $clinicQueryService) {}
    public function getClinicServicesBySpecialityId(int $specialityId)
    {
        return response()->json([
            'success' => true,
            'data' => $this->clinicQueryService->getClinicServicesBySpecialityId($specialityId),
        ]);
    }
    public function getAvailableClinics(int $specialityId, int $serviceId)
    {
        return response()->json([
            'success' => true,
            'data' => $this->clinicQueryService->getAvailableClinics($specialityId, $serviceId),
        ]);
    }
}
