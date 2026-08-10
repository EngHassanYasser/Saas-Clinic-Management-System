<?php
namespace App\DTOs\Services\Appointment;
class IsSlotAvailableDTO {
    public function __construct(
        public readonly int $clinicId,
        public readonly int $doctorId,
        public readonly string $visiteDate,
        public readonly string $slot,
    ){}
}