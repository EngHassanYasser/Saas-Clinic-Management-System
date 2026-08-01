<?php

namespace App\http\Controllers;

use App\Services\ClinicQueryService;

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
