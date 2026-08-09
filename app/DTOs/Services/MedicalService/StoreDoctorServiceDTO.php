<?php

namespace App\DTOs\Services\Clinic\ClinicService;

use App\Http\Requests\MedicalService\StoreMedicalServiceRequest;

class StoreDoctorServiceDTO
{
    public function __construct(
        public readonly int $doctorServiceId,
        public readonly int $doctorId,
        public readonly string $description,
        public readonly float $price,
    ) {}

        public static function fromRequest(StoreMedicalServiceRequest $request):self {
            return new self(
                doctorServiceId:$request->integer('doctorServiceId'),
                doctorId:$request->integer('doctorId'),
                description:$request->integer('description'),
                price:$request->float('price'),
            );
        }
}
