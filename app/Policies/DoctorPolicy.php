<?php

namespace App\Policies;

use App\Enums\EnRoleType;use App\Models\Doctor;
use App\Models\User;

class DoctorPolicy
{
    /**
     * Determine whether the user can view any doctors.
     */
    private function isBelogToClinicOwner(User $user, int $doctorId): bool
    {
        return $user->clinics()
            ->whereHas('doctors', function ($query) use ($doctorId) {
                $query->whereKey($doctorId);
            })->exists();
    }

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
     * Determine whether the user can view the doctor.
     */
    public function view(User $user, Doctor $doctor): bool
    {
        return match ($user->type) {
           EnRoleType::PATIENT => true,

           EnRoleType::CLINIC => $this->isBelogToClinicOwner($user, $doctor->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a doctor.
     */
    public function create(User $user): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC,
           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can update the doctor.
     */
    public function update(User $user, Doctor $doctor): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC => $this->isBelogToClinicOwner($user, $doctor->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the doctor.
     */
    public function delete(User $user, Doctor $doctor): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC => $this->isBelogToClinicOwner($user, $doctor->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can manage the doctor.
     */
    public function manage(User $user, Doctor $doctor): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC => $this->isBelogToClinicOwner($user, $doctor->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
