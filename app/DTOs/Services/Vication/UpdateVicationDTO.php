<?php

namespace App\DTOs\Services\Vacation;

use App\Enums\VacationStatus;
use App\Http\Requests\vacations\UpdateVacationsRequest;

class UpdateVacationDTO
{
    public function __construct(
        public readonly int $doctorId,
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly string $reason,
        public readonly VacationStatus $status
    ) {}

    public static function fromRequest(UpdateVacationsRequest $request): self
    {
        return new self(
            doctorId: $request->integer('doctorId'),
            startDate: $request->string('startDate'),
            endDate: $request->string('endDate'),
            reason: $request->string('reason'),
            status: $request->VacationStastus('status')
        );
    }
}
