<?php

namespace App\services;

use App\Models\ClinicService;
use App\Models\doctor_service_price;
use Illuminate\Support\Facades\Auth;

class  ServiceCatalogService
{
    public function getAllCatalogs()
    {
        return ClinicService::select('id', 'name')->get();
    }
    public function getAllClinicServices()
    {
        return doctor_service_price::with(['clinic', 'doctor', 'clinic_service'])
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
    public function addNew($data)
    {
        $result =    doctor_service_price::create([
            'clinic_id' => Auth::user()->clinic_id,
            'doctor_id' => $data['doctor_id'],
            'clinic_service_id' => $data['clinic_service_id'],
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }
    public function Update($data)
    {
        $result = doctor_service_price::where('id', $data['id'])->update([
            'clinic_id' => Auth::user()->clinic_id,
            'doctor_id' => $data['doctor_id'],
            'clinic_service_id' => $data['clinic_service_id'],
            'price' => $data['price'],
            'description' => $data['description'],
        ]);
    }
    public function deleteById($id)
    {
        return doctor_service_price::destroy($id);
    }
}
