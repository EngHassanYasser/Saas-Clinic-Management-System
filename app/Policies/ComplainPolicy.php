<?php

namespace App\Policies;

use App\Enums\RoleType;
use App\Models\Complain;
use App\Models\User;

class ComplainPolicy
{
    /**
     * Determine whether the user can view any complaints.
     */
    private function isPatientOwnerOfComplain(User $user, Complain $complain): bool
    {
        return $complain->patient_id == $user->id;
    }

    private function isComplainBelongToThatClinicOwner(User $user, int $complainId): bool
    {
        // return $user->clinics()->complains()->whereKey($complainId);

        return $user->clinics()
        ->Where('complains',fn($query)=>$query->whereKey($complainId));
    }

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
     * Determine whether the user can view the complaint.
     */
    public function view(User $user, Complain $complain): bool
    {
        return match ($user->role) {
            RoleType::PATIENT => $this->isPatientOwnerOfComplain($user, $complain),

            RoleType::CLINIC => $this->isComplainBelongToThatClinicOwner($user, $complain->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a complaint.
     */
    public function create(User $user): bool
    {
        return $user->role === RoleType::PATIENT;
    }

    /**
     * Determine whether the user can update the complaint.
     */
    public function update(User $user, Complain $complain): bool
    {
        return match ($user->role) {
            RoleType::PATIENT => $this->isPatientOwnerOfComplain($user, $complain),

            RoleType::CLINIC => $this->isComplainBelongToThatClinicOwner($user, $complain->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the complaint.
     */
    public function delete(User $user, Complain $complain): bool
    {
        return match ($user->role) {
            RoleType::PATIENT => $this->isPatientOwnerOfComplain($user, $complain),

            RoleType::CLINIC => $this->isComplainBelongToThatClinicOwner($user, $complain->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can respond to the complaint.
     */
    public function respond(User $user, Complain $complain): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->isComplainBelongToThatClinicOwner($user, $complain->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can change the complaint status.
     */
    public function changeStatus(User $user, Complain $complain): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->isComplainBelongToThatClinicOwner($user, $complain->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
