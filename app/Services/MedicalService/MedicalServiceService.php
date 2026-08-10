<?php

namespace App\Services\MedicalService;

use App\DTOs\Services\MedicalService\StoreMedicalrviceDTO;
use App\DTOs\Services\MedicalService\UpdateMedicalServiceDTO;
use App\Models\Clinic_doctor_medicalService;
use Illuminate\Database\Eloquent\Collection;

class MedicalServiceService
{
    public function getAllDoctorServices(): Collection
    {
        return Clinic_doctor_medicalService::with([
            'clinic',
            'doctor',
            'medicalService',
        ])->get();
    }

    public function add(StoreMedicalrviceDTO $dto, int $clinicId): Clinic_doctor_medicalService
    {
        return Clinic_doctor_medicalService::create([
            'clinic_id' => $clinicId,
            'doctor_id' => $dto->doctorId,
            'medicalService_id' => $dto->medicalServiceId,
            'price' => $dto->price,
            'description' => $dto->description,
        ]);
    }

    public function update(UpdateMedicalServiceDTO $dto, int $clinicId): bool
    {
        return Clinic_doctor_medicalService::where('id', $dto->medicalServiceId)->update([
            'clinic_id' => $clinicId,
            'doctor_id' => $dto->doctorId,
            'medicalService_id' => $dto->medicalServiceId,
            'price' => $dto->price,
            'description' => $dto->description,
        ]);
    }

    public function deleteById(int $medicalServiceId, int $clinicId): bool
    {
        return Clinic_doctor_medicalService::where('id', $medicalServiceId)
            ->where('clinic_id', $clinicId)->delete();
    }
}
