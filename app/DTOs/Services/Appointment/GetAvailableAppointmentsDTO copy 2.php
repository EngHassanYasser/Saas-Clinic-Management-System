<?php

namespace App\DTOs\Services\Appointment;

class GetAvailableAppointmentsDTO
{
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly string $visisteDate,
    ) {}

}
