<?php

namespace App\Services\ServiceCatalog;

use App\Models\Doctor_service_price;
use Illuminate\Database\Eloquent\Collection;

class ClinicServicePriceService
{
    public function getAllClinicServices(): Collection
    {
        return Doctor_service_price::with([
            'clinic',
            'doctor',
            'clinic_service',
        ])->get();
    }

    public function add(array $data, int $clinicId): Doctor_service_price
    {
        return Doctor_service_price::create([
            'clinic_id' => $clinicId,
            'doctor_id' => $data['doctor_id'],
            'clinic_service_id' => $data['clinic_service_id'],
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }

    public function update(array $data, int $clinicId): bool
    {
        return Doctor_service_price::where('id', $data['id'])->update([
            'clinic_id' => $clinicId,
            'doctor_id' => $data['doctor_id'],
            'clinic_service_id' => $data['clinic_service_id'],
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }

    public function deleteById(int $clinicServiceId): bool
    {
        return Doctor_service_price::destroy($clinicServiceId);
    }
}
