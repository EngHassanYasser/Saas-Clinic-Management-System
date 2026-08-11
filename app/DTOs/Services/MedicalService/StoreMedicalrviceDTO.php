<?php

namespace App\DTOs\Services\Medical_Service;

class StoreMedicalrviceDTO
{
    public function __construct(
        public readonly int $medicalServiceId,
        public readonly int $doctorId,
        public readonly string $description,
        public readonly float $price,
    ) {}

        public static function fromRequest(array $validatedData):self {
            return new self(
                medicalServiceId:$validatedData['medicalServiceId'],
                doctorId:$validatedData['doctorId'],
                description:$validatedData['description'],
                price:$validatedData['price'],
            );
        }
}
