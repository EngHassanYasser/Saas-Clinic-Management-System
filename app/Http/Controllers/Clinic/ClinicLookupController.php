<?php

namespace App\http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Services\Clinic\ClinicQueryService;

class ClinicLookupController extends Controller
{
    public function __construct(private ClinicQueryService $clinicQueryService) {}
    public function getDoctorServicesBySpecialityId(int $specialityId)
    {
        return response()->json([
            'success' => true,
            'data' => $this->clinicQueryService->getDoctorServicesBySpecialityId($specialityId),
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
