<?php

namespace App\Services;

use App\Models\ClinicService;
use App\Models\Doctor_service_price;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class  ServiceCatalogService
{
    public function getAllCatalogs():Collection
    {
        return ClinicService::select('id', 'name', 'speciality_id')->get();
    }
    public function getAllClinicServices():Collection
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
    public function addNew(array $data): Doctor_service_price
    {
        return Doctor_service_price::create([
            'clinic_id' => Auth::user()->clinic_id,
            'doctor_id' => $data['doctor_id'],
            'clinic_service_id' => $data['clinic_service_id'],
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }
    public function Update(array $data): bool
    {
        return Doctor_service_price::where('id', $data['id'])->update([
            'clinic_id' => Auth::user()->clinic_id,
            'doctor_id' => $data['doctor_id'],
            'clinic_service_id' => $data['clinic_service_id'],
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }
    public function deleteById(int $id): bool
    {
        return Doctor_service_price::destroy($id);
    }
}
