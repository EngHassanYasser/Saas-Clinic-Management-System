<?php

namespace App\DTOs\Services\AppointmentService;

use App\Models\Appointment;

class RescheduleDTO
{
    public function __construct(
        public readonly Appointment $appointment,
        public readonly string $visiteDate,
        public readonly string $startTime,
    ) {}

}
