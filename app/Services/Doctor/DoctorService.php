<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use App\Models\Doctor_service_price;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    public function add(array $data,int $clinicId): Doctor
    {
        return  DB::transaction(function () use ($data,$clinicId) {

            $doctor = Doctor::create([
                'name' => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);
            $doctor->specialities()->attach([$data['speciality_id']]);
            $doctor->clinics()->attach($clinicId);
            if (!empty($data['image'])) {
                $doctor->addMedia($data['image'])
                    ->toMediaCollection('avatar');
            }
            return $doctor;
        });
    }
    public function update(array $data, int $doctorId,$clinicId): bool
    {
        return DB::transaction(function () use ($data,$doctorId,$clinicId) {

            $doctor = Doctor::findOrFail($doctorId);

            $updated = $doctor->update([
                'name'  => $data['name'],
                'phone' => $data['phone'],
                'email' => $data['email'],
            ]);

            $doctor->specialities()->sync([$data['speciality_id']]);
            $doctor->clinics()->updateExistingPivot(
                $clinicId,
                [
                    'is_active' => $data['is_active'],
                ]
            );
            if (!empty($data['image'])) {

                $doctor->clearMediaCollection('avatar');

                $doctor->addMedia($data['image'])
                    ->toMediaCollection('avatar');
            }

            return $updated;
        });
    }
    public function deleteById(int $doctorId,int $clinicId): bool
    {
        return DB::transaction(function () use ($doctorId,$clinicId) {

            $doctor = Doctor::findOrFail($doctorId);

            $doctor->clinics()->detach($clinicId);

            Doctor_service_price::where('doctor_id', $doctorId)->delete();

            $doctor->specialities()->detach();

            $deleted = $doctor->delete();

            return $deleted;
        });
    }
}
