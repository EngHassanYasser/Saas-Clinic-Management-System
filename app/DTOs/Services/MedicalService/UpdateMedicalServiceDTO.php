<?php

namespace App\DTOs\Services\Medical_Service;

use App\Http\Requests\Medical_Service\UpdateMedical_ServiceRequest;

class UpdateMedical_ServiceDTO
{
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly int $medicalServiceId,
        public readonly float $price,
        public readonly string $description
    ) {}

    public static function fromRequest(UpdateMedical_ServiceRequest $request): self
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
