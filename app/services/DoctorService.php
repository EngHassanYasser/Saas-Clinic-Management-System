<?php

namespace App\services;

use App\Models\doctor;
use App\Models\doctor_service_price;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    public function getDoctorsNames()
    {
        return Doctor::select('id', 'name')->get();
    }
    public function getAll()
    {
        return doctor::with('specialities')->get()->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'phone' => $doctor->phone,
                'email' => $doctor->email,
                'Consultation_Fee' => doctor_service_price::where('doctor_id', $doctor->id)
                    ->whereHas('clinic_service', function ($q) {
                        $q->where('name', 'كشف');
                    })->value('price') ?? 'لا توجد خدمة',
                'specialty' => $doctor->specialities->pluck('name')->implode(', '),
                'active' => $doctor->active ?? true,
                'image' => $doctor->avatar_url,
            ];
        });
    }
    public function addNew($data)
    {
        return  DB::transaction(function () use ($data) {

            $doctor = Doctor::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);
            $doctor->specialities()->sync([$data['speciality_id']]);
            if (!empty($data['image'])) {
                $doctor->addMedia($data['image'])
                    ->toMediaCollection('avatar');
            }

            return $doctor;
        });
    }
    public function deleteById($id)
    {
        return DB::transaction(function () use ($id) {

            doctor_service_price::where('doctor_id', $id)->delete();

            $deleted = Doctor::destroy($id);

            return $deleted > 0;
        });
    }
}
