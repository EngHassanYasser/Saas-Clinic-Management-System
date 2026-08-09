<?php

namespace App\DTOs\Services\AppointmentService;

use App\Http\Requests\appointments\StoreAppointmentRequest;

class StoreAppointmentDTO
{
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly int $DoctorServiceId,
        public readonly string $visiteDate,
        public readonly string $slot,

    ) {}


    public static function fromRequest(StoreAppointmentRequest $request): self
    {
        return new self(
            clinicId: $request->integer('clinic_id'),
            doctorId:$request->integer('doctor_id'),
            DoctorServiceId: $request->integer('doctor_service_id'),
            visiteDate: $request->string('visite_date'),
            slot: $request->string('slot'),
        );
    }
}
