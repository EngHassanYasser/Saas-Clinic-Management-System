<?php

namespace App\DTOs\Services\Medical_Service;

use App\Http\Requests\Medical_Service\StoreMedical_ServiceRequest;

class StoreMedicalrviceDTO
{
    public function __construct(
        public readonly int $medicalServiceId,
        public readonly int $doctorId,
        public readonly string $description,
        public readonly float $price,
    ) {}

        public static function fromRequest(StoreMedical_ServiceRequest $request):self {
            return new self(
                medicalServiceId:$request->integer('medicalServiceId'),
                doctorId:$request->integer('doctorId'),
                description:$request->integer('description'),
                price:$request->float('price'),
            );
        }
}
