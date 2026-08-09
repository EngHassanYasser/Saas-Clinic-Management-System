<?php

namespace App\Services\MedicalService;

use App\DTOs\Services\Clinic\ClinicService\StoreDoctorServiceDTO;
use App\DTOs\Services\DoctorService\UpdateDoctorServiceDTO;
use App\Models\Doctor_service_price;
use Illuminate\Database\Eloquent\Collection;

class MedicalServiceService
{
    public function getAllDoctorServices(): Collection
    {
        return Doctor_service_price::with([
            'clinic',
            'doctor',
            'doctorService',
        ])->get();
    }

    public function add(StoreDoctorServiceDTO $dto, int $clinicId): Doctor_service_price
    {
        return Doctor_service_price::create([
            'clinic_id' => $clinicId,
            'doctor_id' => $dto->doctorId,
            'doctorService_id' => $dto->doctorServiceId,
            'price' =>$dto->price,
            'description' => $dto->description,
        ]);
    }

    public function update(UpdateDoctorServiceDTO $dto, int $clinicId): bool
    {
        return Doctor_service_price::where('id', $dto->doctorServiceId)->update([
            'clinic_id' => $clinicId,
            'doctor_id' => $dto->doctorId,
            'doctorService_id' => $dto->doctorServiceId,
            'price' => $dto->price,
            'description' => $dto->description,
        ]);
    }

    public function deleteById(int $clinicServiceId): bool
    {
        return Doctor_service_price::destroy($clinicServiceId);
    }
}
