<?php

namespace App\DTOs\Services\MedicalService;

use App\Http\Requests\MedicalService\StoreMedicalServiceRequest;

class StoreMedicalrviceDTO
{
    public function __construct(
        public readonly int $medicalServiceId,
        public readonly int $doctorId,
        public readonly string $description,
        public readonly float $price,
    ) {}

        public static function fromRequest(StoreMedicalServiceRequest $request):self {
            return new self(
                medicalServiceId:$request->integer('medicalServiceId'),
                doctorId:$request->integer('doctorId'),
                description:$request->integer('description'),
                price:$request->float('price'),
            );
        }
}
