<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Enums\RoleType;
use App\Exceptions\UnauthorizedException;
use App\Models\Appointment;
use App\Models\User;

class AppointmentStatusService
{
    public function __construct(private AppointmentQueryService $appointmentQueryService){}
    public function changeStatus(AppointmentStatus $status, int $appointmentId,User $user): bool
    {
        $appointment = $this->appointmentQueryService->findAppointment($appointmentId, $user);

        $this->authorizeStatusChange($appointment, $status, $user);

        return $appointment->update([
            'status' => $status,
        ]);
    }
    private function authorizeStatusChange(
        Appointment $appointment,
        AppointmentStatus $status,
        User $user
    ): void {
        if (
            $user->type === RoleType::PATIENT->value &&
            ! $this->canPatientChangeStatus($appointment->status, $status)
        ) {
            throw new UnauthorizedException();
        }

        if (
            $user->type === RoleType::CLINIC->value &&
            ! $this->canClinicChangeStatus($appointment->status, $status)
        ) {
            throw new UnauthorizedException();
        }
    }
    private function canClinicChangeStatus(
        AppointmentStatus $currentStatus,
        AppointmentStatus $newStatus
    ): bool {
        return match ($currentStatus) {
            AppointmentStatus::PENDING =>
            in_array($newStatus, [
                AppointmentStatus::CONFIRMED,
                AppointmentStatus::CANCELLED,
            ]),

            AppointmentStatus::CONFIRMED =>
            in_array($newStatus, [
                AppointmentStatus::IN_PROGRESS,
                AppointmentStatus::CANCELLED,
                AppointmentStatus::NO_SHOW,
                AppointmentStatus::RESCHEDULED,
            ]),

            AppointmentStatus::IN_PROGRESS =>
            $newStatus === AppointmentStatus::COMPLETED,

            default => false,
        };
    }
    private function canPatientChangeStatus(
        AppointmentStatus $currentStatus,
        AppointmentStatus $newStatus
    ): bool {
        return match ($currentStatus) {
            AppointmentStatus::PENDING =>
            $newStatus === AppointmentStatus::CANCELLED,

            AppointmentStatus::CONFIRMED =>
            $newStatus === AppointmentStatus::CANCELLED,

            default => false,
        };
    }
}
