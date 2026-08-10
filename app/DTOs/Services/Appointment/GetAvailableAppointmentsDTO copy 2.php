<?php

namespace App\DTOs\Services\Appointment;

use App\Models\Clinic;
use App\Models\Doctor;

class GetAvailableAppointmentsDTO
{
    public function __construct(
        public readonly Clinic $clinic,
        public readonly Doctor $doctor,
        public readonly string $visisteDate,
    ) {}

}
