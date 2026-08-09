<?php

namespace App\DTOs\Services\AppointmentService;

class GetAvailableAppointmentsDTO
{
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly string $visisteDate,
    ) {}

}
