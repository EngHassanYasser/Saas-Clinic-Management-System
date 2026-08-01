<?php

namespace App\Http\Controllers;

use App\Services\DoctorQueryService;

class DoctorQueryController extends Controller
{
    public function __construct(private DoctorQueryService $doctorQueryService) {}
    public function getAvailableDoctors(int $clinicId, int $specialityId, int $serviceId)
    {
        return response()->json([
            'success' => true,
            'data' => $this->doctorQueryService->getAvailableDoctors($clinicId, $specialityId, $serviceId),
        ]);
    }
}
