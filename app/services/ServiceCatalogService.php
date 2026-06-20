<?php
namespace App\services;

use App\Models\ClinicService;
use App\Models\doctor_service_price;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class  ServiceCatalogService {
    public function addNew($data) {
          DB::transaction(function () use ($data) {
            $ClinicService = ClinicService::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
            ]);

            doctor_service_price::create([
                'clinic_id' => Auth::user()->clinic_id,
                'doctor_id' => $data['doctor_id'],
                'service_id' => $ClinicService->id,
                'price' => $data['price'],
            ]);
        });

    }
}