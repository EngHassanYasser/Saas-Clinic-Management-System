<?php

namespace App\DTOs\Services\Plan\PlanService;

use App\Enums\PlanStatus;
use App\Http\Requests\plans\StorePlanRequest;

class StorePlanDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $maxDoctors,
        public readonly int $monthlyAppointmentsLimit,
        public readonly float $monthlyPrice,
        public readonly PlanStatus $status,
    ) {}

    public static function fromRequest(StorePlanRequest $request): self
    {
        return new self(
            name: $request->string('name'),
            maxDoctors: $request->integer('maxDoctors'),
            monthlyAppointmentsLimit: $request->integetr('monthlyAppointmentsLimit'),
            monthlyPrice: $request->float('monthlyPrice'),
            status: $request->PlanStatus('status'),
        );
    }
}
