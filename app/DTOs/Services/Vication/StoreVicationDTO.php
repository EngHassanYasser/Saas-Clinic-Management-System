<?php

namespace App\DTOs\Services\Vacation;

use App\Enums\VacationStatus;
use App\Http\Requests\vacations\StoreVacationsRequest;

class StoreVacationDTO
{
    public function __construct(
        public readonly int $doctorId,
        public readonly string $StartDate,
        public readonly string $endDate,
        public readonly string $reason,
        public readonly VacationStatus $status,
    ) {}

    public static function fromRequest(StoreVacationsRequest $request): self
    {
        return new self(
            doctorId: $request->integer('doctorId'),
            StartDate: $request->string('StartDate'),
            endDate: $request->string('endDate'),
            reason: $request->string('reason'),
            status: $request->VacationStatus('status'),
        );
    }
}
