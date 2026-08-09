<?php

namespace App\Policies;

use App\Enums\RoleType;
use App\Models\Clinic;
use App\Models\User;

class ClinicPolicy
{
    private function isOwnerOfClinic(User $user, int $clinicId): bool
    {
        return $user->clinics()
            ->whereKey($clinicId)
            ->exists();
    }

    /**
     * Determine whether the user can view any clinics.
     */
    public function viewAny(User $user): bool
    {
        return match ($user->role) {
            RoleType::PATIENT,
            RoleType::CLINIC,
            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can view the clinic.
     */
    public function view(User $user, Clinic $clinic): bool
    {
        return match ($user->role) {
            RoleType::PATIENT => true,

            RoleType::CLINIC => $this->isOwnerOfClinic($user, $clinic->id),
            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a clinic.
     */
    public function create(User $user): bool
    {
        return $user->role === RoleType::SUPER_ADMIN;
    }

    /**
     * Determine whether the user can update the clinic.
     */
    public function update(User $user, Clinic $clinic): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->isOwnerOfClinic($user, $clinic->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the clinic.
     */
    public function delete(User $user, Clinic $clinic): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->isOwnerOfClinic($user, $clinic->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can manage the clinic.
     */
    public function manage(User $user, Clinic $clinic): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->isOwnerOfClinic($user, $clinic->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can update clinic settings.
     */
    public function updateSettings(User $user, Clinic $clinic): bool
    {
        return $this->manage($user, $clinic);
    }
}
