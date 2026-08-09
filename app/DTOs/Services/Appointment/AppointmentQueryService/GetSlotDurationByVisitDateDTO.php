<?php

namespace App\DTOs\Services\Appointment\AppointmentQueryService;

class GetSlotDurationByVisitDateDTO
{
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly string $visiteDate,
    ) {}
}
