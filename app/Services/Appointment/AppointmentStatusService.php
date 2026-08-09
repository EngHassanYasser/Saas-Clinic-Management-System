<?php

namespace App\Services\Appointment;

use App\Enums\AppointmentStatus;
use App\Exceptions\UnauthorizedException;
use App\Models\Appointment;

class AppointmentStatusService
{
    public function changeStatus(Appointment $appointment, AppointmentStatus $status): bool
    {
        $this->authorizeStatusChange($appointment, $status);

        return $appointment->update([
            'status' => $status,
        ]);
    }

    private function authorizeStatusChange(
        Appointment $appointment,
        AppointmentStatus $status,
    ): void {
        if (
            ! $this->canPatientChangeStatus($appointment->status, $status)
            || ! $this->canClinicChangeStatus($appointment->status, $status)
        ) {
            throw new UnauthorizedException;
        }
    }

    private function canClinicChangeStatus(
        AppointmentStatus $currentStatus,
        AppointmentStatus $newStatus
    ): bool {
        return match ($currentStatus) {
            AppointmentStatus::PENDING => in_array($newStatus, [
                AppointmentStatus::CONFIRMED,
                AppointmentStatus::CANCELLED,
            ]),

            AppointmentStatus::CONFIRMED => in_array($newStatus, [
                AppointmentStatus::IN_PROGRESS,
                AppointmentStatus::CANCELLED,
                AppointmentStatus::NO_SHOW,
                AppointmentStatus::RESCHEDULED,
            ]),

            AppointmentStatus::IN_PROGRESS => $newStatus === AppointmentStatus::COMPLETED,

            default => false,
        };
    }

    private function canPatientChangeStatus(
        AppointmentStatus $currentStatus,
        AppointmentStatus $newStatus
    ): bool {
        return match ($currentStatus) {
            AppointmentStatus::PENDING => $newStatus === AppointmentStatus::CANCELLED,

            AppointmentStatus::CONFIRMED => $newStatus === AppointmentStatus::CANCELLED,

            default => false,
        };
    }
}
