<?php

namespace App\DTOs\Services\Plan;

use App\Enums\EnPlanStatus;

class StorePlanDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $maxDoctors,
        public readonly int $monthlyAppointmentsLimit,
        public readonly float $monthlyPrice,
        public readonly EnPlanStatus $status,
    ) {}

    public static function fromRequest(array $validatedData): self
    {
        return new self(
            name: $validatedData['name'],
            maxDoctors: $validatedData['maxDoctors'],
            monthlyAppointmentsLimit: $validatedData['monthlyAppointmentsLimit'],
            monthlyPrice: $validatedData['monthlyPrice'],
            status: $validatedData['status'],
        );
    }
}
