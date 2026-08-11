<?php

namespace App\Services\MedicalService;

use App\Models\Medical_service;
use Illuminate\Support\Facades\Cache;

class MedicalServiceQueryService
{
    public function getAll(): array
    {
        return Cache::remember(
            'Medical_Service.all',
            now()->addMinutes(5),
            fn () => Medical_service::select('id', 'name', 'speciality_id')->get()->toArray()
        );
    }
}

