<?php

namespace App\Policies;

use App\Enums\EnRoleType;use App\Models\Appointment;
use App\Models\User;

class AppointmentPolicy
{
    private function isPatientOwner(User $user, Appointment $appointment): bool
    {
        return $appointment->patient_id === $user->id;
    }

    private function belongsToClinic(User $user, Appointment $appointment): bool
    {
        return $user->clinics()
            ->whereKey($appointment->clinic_id)
            ->exists();
    }

    /**
     * Determine whether the user can view any appointments.
     */
    public function viewAny(User $user): bool
    {
        return match ($user->type) {
           EnRoleType::PATIENT,
           EnRoleType::CLINIC,
           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can view the appointment.
     */
    public function view(User $user, Appointment $appointment): bool
    {
        return match ($user->type) {
           EnRoleType::PATIENT => $this->isPatientOwner($user, $appointment),

           EnRoleType::CLINIC => $this->belongsToClinic($user, $appointment),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create an appointment.
     */
    public function create(User $user): bool
    {
        return $user->type ===EnRoleType::PATIENT;
    }

    /**
     * Determine whether the user can update the appointment.
     */
    public function update(User $user, Appointment $appointment): bool
    {
        return match ($user->type) {
           EnRoleType::PATIENT => $this->isPatientOwner($user, $appointment),

           EnRoleType::CLINIC => $this->belongsToClinic($user, $appointment),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can cancel the appointment.
     */
    public function cancel(User $user, Appointment $appointment): bool
    {
        return match ($user->type) {
           EnRoleType::PATIENT => $this->isPatientOwner($user, $appointment),

           EnRoleType::CLINIC => $this->belongsToClinic($user, $appointment),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can confirm the appointment.
     */
    public function confirm(User $user, Appointment $appointment): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC => $this->belongsToClinic($user, $appointment),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can reschedule the appointment.
     */
    public function reschedule(User $user, Appointment $appointment): bool
    {
        return match ($user->type) {
           EnRoleType::PATIENT => $this->isPatientOwner($user, $appointment),

           EnRoleType::CLINIC => $this->belongsToClinic($user, $appointment),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can change the appointment status.
     */
    public function changeStatus(User $user, Appointment $appointment): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC => $this->belongsToClinic($user, $appointment),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the appointment.
     */
    public function delete(User $user, Appointment $appointment): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC => $this->belongsToClinic($user, $appointment),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
