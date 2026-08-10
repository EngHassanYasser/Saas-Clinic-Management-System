<?php

namespace App\DTOs\Services\Plan;

use App\Enums\PlanStatus;
use App\Http\Requests\plans\UpdatePlanRequest;

class UpdatePlanDTO
{
    public function __construct(
        public readonly string $name,
        public readonly int $maxDoctors,
        public readonly int $monthlyAppointmentsLimit,
        public readonly float $monthlyPrice,
        public readonly PlanStatus $status

    ) {}

    public static function fromRequest(UpdatePlanRequest $request): self
    {
        return new self(
            name: $request->string('name'),
            maxDoctors: $request->integer('maxDoctor'),
            monthlyAppointmentsLimit: $request->integer('monthlyAppointmentsLimit'),
            monthlyPrice: $request->float('monthlyPrice'),
            status: $request->PlanStatus('status')
        );
    }
}
