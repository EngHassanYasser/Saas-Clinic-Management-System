<?php

namespace App\DTOs\Services\Medical_Service;

class UpdateMedical_ServiceDTO
{
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly int $medicalServiceId,
        public readonly float $price,
        public readonly string $description
    ) {}

    public static function fromRequest(array $validatedData): self
    {
        return new self(
            clinicId: $validatedData['clinicId'],
            doctorId: $validatedData['doctorId'],
            medicalServiceId: $validatedData['medicalServiceId'],
            price: $validatedData['price'],
            description: $validatedData['description']
        );
    }
}
