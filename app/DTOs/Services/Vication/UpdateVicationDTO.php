<?php

namespace App\DTOs\Services\Vacation;

use App\Enums\EnVacationStatus;

class UpdateVacationDTO
{
    public function __construct(
        public readonly int $doctorId,
        public readonly string $startDate,
        public readonly string $endDate,
        public readonly string $reason,
        public readonly EnVacationStatus $status
    ) {}

    public static function fromRequest(array $validatedData): self
    {
        return new self(
            doctorId: $validatedData['doctorId'],
            startDate: $validatedData['startDate'],
            endDate: $validatedData['endDate'],
            reason: $validatedData['reason'],
            status: $validatedData['status']
        );
    }
}
