<?php

namespace App\Services\Doctor;

use App\DTOs\Services\Doctor\StoreDoctorDTO;
use App\DTOs\Services\Doctor\UpdateDoctorDTO;
use App\Models\Doctor;
use App\Models\Clinic_doctor_medicalService;
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

    public function update(UpdateDoctorDTO $dto, int $doctorId,int $clinicId): bool
    {
        return DB::transaction(function () use ($dto, $doctorId, $clinicId) {

            $doctor = Doctor::findOrFail($doctorId);

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

    public function deleteById(int $doctorId, int $clinicId): bool
    {
        return DB::transaction(function () use ($doctorId, $clinicId) {

            $doctor = Doctor::findOrFail($doctorId);

            $doctor->clinics()->detach($clinicId);

            Clinic_doctor_medicalService::where('doctor_id', $doctorId)
                ->where('clinic_id', $clinicId)
                ->delete();

            return true;
        });
    }
}
