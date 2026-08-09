<?php

namespace App\DTOs\Services\AppointmentService;

class GetBookedSlotsDTO
{
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly string $visisteDate,
    ) {}

}
