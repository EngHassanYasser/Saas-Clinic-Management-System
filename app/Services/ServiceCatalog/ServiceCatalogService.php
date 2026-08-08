<?php

namespace App\Services\ServiceCatalog;

use App\Models\ClinicService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class ServiceCatalogService
{
    public function getAllCatalogs(): array
    {
        return Cache::remember(
            'clinicService.all',
            now()->addMinutes(5),
            fn () => ClinicService::select('id', 'name', 'speciality_id')->get()->toArray()
        );
    }
}

