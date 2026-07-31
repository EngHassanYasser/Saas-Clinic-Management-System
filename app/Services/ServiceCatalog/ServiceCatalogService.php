<?php

namespace App\Services;

use App\Models\ClinicService;
use Illuminate\Database\Eloquent\Collection;

class ServiceCatalogService
{
    public function getAllCatalogs(): Collection
    {
        return ClinicService::select('id', 'name', 'speciality_id')->get();
    }
}
