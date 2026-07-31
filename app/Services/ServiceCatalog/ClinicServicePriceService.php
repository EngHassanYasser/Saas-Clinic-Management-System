<?php

namespace App\Services;

use App\Models\Doctor_service_price;
use Illuminate\Database\Eloquent\Collection;

class ClinicServicePriceService
{
    public function getAllClinicServices(): Collection
    {
        return Doctor_service_price::with(['clinic', 'doctor', 'clinic_service'])
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'clinic_name' => $item->clinic->name,
                    'doctor_id' => $item->doctor->id,
                    'doctor_name' => $item->doctor->name,
                    'clinic_service_id' => $item->clinic_service->id,
                    'service_name' => $item->clinic_service->name,
                    'description' => $item->description,
                    'price' => $item->price,
                ];
            });
    }

    public function add(array $data,int $clinicId): Doctor_service_price
    {
        return Doctor_service_price::create([
            'clinic_id' => $clinicId,
            'doctor_id' => $data['doctor_id'],
            'clinic_service_id' => $data['clinic_service_id'],
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }

    public function update(array $data,int $clinicId): bool
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
