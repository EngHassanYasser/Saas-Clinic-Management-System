<?php

namespace App\services;

use App\Models\doctor;
use App\Models\doctor_service_price;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    public function getDoctorsNames()
    {
        DB::enableQueryLog();
        return Doctor::select('id', 'name')->get();
        dd(DB::getQueryLog());
    }
    public function getAll()
    {
        // DB::enableQueryLog();
        return doctor::with(['specialities', 'doctor_service_price.clinic_service', 'media'])->get()->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'phone' => $doctor->phone,
                'email' => $doctor->email,
                'Consultation_Fee' => optional(
                    $doctor->doctor_service_price
                        ->first(fn($item) => $item->clinic_service?->name === 'كشف')
                )->price ?? 'لا توجد خدمة',
                'speciality' => optional($doctor->specialities->first())->only(['id', 'name']),
                'active' => $doctor->active ?? true,
                'image' => $doctor->getFirstMediaUrl('avatar') ?: asset('storage/default_profile_image.jpg'),
            ];
        });
        // dd(DB::getQueryLog());
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
    public function update($data, $id)
    {
        return DB::transaction(function () use ($data, $id) {

            $doctor = Doctor::findOrFail($id);

            $updated = $doctor->update([
                'name'  => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);

            $doctor->specialities()->sync([$data['speciality_id']]);

            if (!empty($data['image'])) {

                $doctor->clearMediaCollection('avatar');

                $doctor->addMedia($data['image'])
                    ->toMediaCollection('avatar');
            }

            return $updated;
        });
    }
}
