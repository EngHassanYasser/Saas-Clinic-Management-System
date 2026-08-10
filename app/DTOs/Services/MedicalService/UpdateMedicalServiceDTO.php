<?php

namespace App\DTOs\Services\MedicalService;

use App\Http\Requests\MedicalService\UpdateMedicalServiceRequest;

class UpdateMedicalServiceDTO
{
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly int $medicalServiceId,
        public readonly float $price,
        public readonly string $description
    ) {}

    public static function fromRequest(UpdateMedicalServiceRequest $request): self
    {
        return new self(
            clinicId: $request->integer('clinicId'),
            doctorId: $request->integer('doctorId'),
            medicalServiceId: $request->integer('medicalServiceId'),
            price: $request->float('price'),
            description: $request->string('description')
        );
    }
}
