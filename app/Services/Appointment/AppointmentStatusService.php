<?php

namespace App\Services\Appointment;

use App\Enums\EnAppointmentStatus;
use App\Exceptions\UnauthorizedException;
use App\Models\Appointment;

class AppointmentStatusService
{
    public function changeStatus(Appointment $appointment, EnAppointmentStatus $status): bool
    {
        $this->authorizeStatusChange($appointment, $status);

        return $appointment->update([
            'status' => $status,
        ]);
    }

    private function authorizeStatusChange(
        Appointment $appointment,
        EnAppointmentStatus $status,
    ): void {
        if (
            ! $this->canPatientChangeStatus($appointment->status, $status)
            || ! $this->canClinicChangeStatus($appointment->status, $status)
        ) {
            throw new UnauthorizedException;
        }
    }

    private function canClinicChangeStatus(
        EnAppointmentStatus $currentStatus,
        EnAppointmentStatus $newStatus
    ): bool {
        return match ($currentStatus) {
            EnAppointmentStatus::PENDING => in_array($newStatus, [
                EnAppointmentStatus::CONFIRMED,
                EnAppointmentStatus::CANCELLED,
            ]),

            EnAppointmentStatus::CONFIRMED => in_array($newStatus, [
                EnAppointmentStatus::IN_PROGRESS,
                EnAppointmentStatus::CANCELLED,
                EnAppointmentStatus::NO_SHOW,
                EnAppointmentStatus::RESCHEDULED,
            ]),

            EnAppointmentStatus::IN_PROGRESS => $newStatus === EnAppointmentStatus::COMPLETED,

            default => false,
        };
    }

    private function canPatientChangeStatus(
        EnAppointmentStatus $currentStatus,
        EnAppointmentStatus $newStatus
    ): bool {
        return match ($currentStatus) {
            EnAppointmentStatus::PENDING => $newStatus === EnAppointmentStatus::CANCELLED,

            EnAppointmentStatus::CONFIRMED => $newStatus === EnAppointmentStatus::CANCELLED,

            default => false,
        };
    }
}
