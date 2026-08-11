<?php

namespace App\Services\MedicalService;

use App\DTOs\Services\Medical_Service\StoreMedicalrviceDTO;
use App\DTOs\Services\Medical_Service\UpdateMedical_ServiceDTO;
use App\Models\Clinic_doctor_medical_service;
use Illuminate\Database\Eloquent\Collection;

class MedicalServiceService
{
    public function getAllDoctorServices(): Collection
    {
        return Clinic_doctor_medical_service::with([
            'clinic',
            'doctor',
            'medical_service',
        ])->get();
    }

    public function add(StoreMedicalrviceDTO $dto, int $clinicId): Clinic_doctor_medical_service
    {
        return Clinic_doctor_medical_service::create([
            'clinic_id' => $clinicId,
            'doctor_id' => $dto->doctorId,
            'medical_service_id' => $dto->medicalServiceId,
            'price' => $dto->price,
            'description' => $dto->description,
        ]);
    }

    public function update(UpdateMedical_ServiceDTO $dto, int $clinicId): bool
    {
        return Clinic_doctor_medical_service::where('id', $dto->medicalServiceId)->update([
            'clinic_id' => $clinicId,
            'doctor_id' => $dto->doctorId,
            'medical_service_id' => $dto->medicalServiceId,
            'price' => $dto->price,
            'description' => $dto->description,
        ]);
    }

    public function deleteById(int $medicalServiceId, int $clinicId): bool
    {
        return Clinic_doctor_medical_service::where('id', $medicalServiceId)
            ->where('clinic_id', $clinicId)->delete();
    }
}
