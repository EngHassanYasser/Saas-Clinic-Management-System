<?php

namespace App\Services\Doctor;

use App\DTOs\Services\Doctor\StoreDoctorDTO;
use App\DTOs\Services\Doctor\UpdateDoctorDTO;
use App\Models\Doctor;
use App\Models\Clinic_doctor_medical_service;
use Illuminate\Support\Facades\DB;

class DoctorService
{
    public function add(StoreDoctorDTO $dto, int $clinicId): Doctor
    {
        return DB::transaction(function () use ($dto, $clinicId) {

            $doctor = Doctor::create([
                'name' => $dto->name,
                'phone' => $dto->phone,
                'email' => $dto->email,
            ]);
            $doctor->specialities()->attach([$dto->specialityId]);
            $doctor->clinics()->attach($clinicId);
            if (! empty($data['image'])) {
                $doctor->addMedia($data['image'])
                    ->toMediaCollection('avatar');
            }

            return $doctor;
        });
    }

    public function update(UpdateDoctorDTO $dto, Doctor $doctor,int $clinicId): bool
    {
        return DB::transaction(function () use ($dto, $doctor, $clinicId) {
            
            $updated = $doctor->update([
                'name' => $dto->name,
                'phone' => $dto->phone,
                'email' => $dto->email,
            ]);

            $doctor->specialities()->sync([$dto->specialityId]);
            $doctor->clinics()->updateExistingPivot(
                $clinicId,
                [
                    'is_active' => $dto->isActive,
                ]
            );
            if ($dto->image) {

                $doctor->clearMediaCollection('avatar');

                $doctor->addMedia($dto->image)
                    ->toMediaCollection('avatar');
            }

            return $updated;
        });
    }

    public function delete(Doctor $doctor, int $clinicId): bool
    {
        return DB::transaction(function () use ($doctor, $clinicId) {

            $doctor->clinics()->detach($clinicId);

            Clinic_doctor_medical_service::where('doctor_id', $doctor->id)
                ->where('clinic_id', $clinicId)
                ->delete();

            return true;
        });
    }
}
