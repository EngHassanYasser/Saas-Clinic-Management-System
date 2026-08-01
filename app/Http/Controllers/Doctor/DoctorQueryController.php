<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Services\Doctor\DoctorQueryService;

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
