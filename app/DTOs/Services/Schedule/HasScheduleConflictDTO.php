<?php

namespace App\DTOs\Services\Schedule;

class HasScheduleConflictDTO
{
    public function __construct(
        public readonly string $startTime,
        public readonly string $endTime,
        public readonly int $slotDuration,
        public readonly ?string $startBreak,
        public readonly ?string $endBreak,
        public readonly bool $isAvailable,
        public readonly array $dayIds,
    ) {}
}
