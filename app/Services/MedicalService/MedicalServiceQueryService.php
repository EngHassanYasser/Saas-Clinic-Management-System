<?php

namespace App\Services\MedicalService;

use App\Models\MedicalService as MedicalServiceModel;
use Illuminate\Support\Facades\Cache;

class MedicalServiceQueryService
{
    public function getAll(): array
    {
        return Cache::remember(
            'clinicService.all',
            now()->addMinutes(5),
            fn () => MedicalServiceModel::select('id', 'name', 'speciality_id')->get()->toArray()
        );
    }
}

