<?php

namespace App\DTOs\Services\DoctorService;

use App\Http\Requests\MedicalService\UpdateMedicalServiceRequest;

class UpdateDoctorServiceDTO
{
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly int $doctorServiceId,
        public readonly float $price,
        public readonly string $description
    ) {}

    public static function fromRequest(UpdateMedicalServiceRequest $request): self
    {
        return new self(
            clinicId: $request->integer('clinicId'),
            doctorId: $request->integer('doctorId'),
            doctorServiceId: $request->integer('doctorServiceId'),
            price: $request->float('price'),
            description: $request->string('description')
        );
    }
}
