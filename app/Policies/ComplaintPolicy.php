<?php

namespace App\Policies;

use App\Enums\EnRoleType;
use App\Models\Complaint;
use App\Models\User;

class ComplaintPolicy
{
    /**
     * Determine whether the user can view any complaintts.
     */
    private function isPatientOwnerOfComplaint(User $user, Complaint $complaint): bool
    {
        return $complaint->patient_id == $user->id;
    }

    private function isComplaintBelongToThatClinicOwner(User $user, int $complaintId): bool
    {
        // return $user->clinics()->complaintts()->whereKey($complaintId);

        return $user->clinics()
        ->Where('complaintts',fn($query)=>$query->whereKey($complaintId));
    }

    public function viewAny(User $user): bool
    {
        return match ($user->role) {
           EnRoleType::PATIENT,
           EnRoleType::CLINIC,
           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can view the complaintt.
     */
    public function view(User $user, Complaint $complaint): bool
    {
        return match ($user->role) {
           EnRoleType::PATIENT => $this->isPatientOwnerOfComplaint($user, $complaint),

           EnRoleType::CLINIC => $this->isComplaintBelongToThatClinicOwner($user, $complaint->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a complaintt.
     */
    public function create(User $user): bool
    {
        return $user->role ===EnRoleType::PATIENT;
    }

    /**
     * Determine whether the user can update the complaintt.
     */
    public function update(User $user, Complaint $complaint): bool
    {
        return match ($user->role) {
           EnRoleType::PATIENT => $this->isPatientOwnerOfComplaint($user, $complaint),

           EnRoleType::CLINIC => $this->isComplaintBelongToThatClinicOwner($user, $complaint->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the complaintt.
     */
    public function delete(User $user, Complaint $complaint): bool
    {
        return match ($user->role) {
           EnRoleType::PATIENT => $this->isPatientOwnerOfComplaint($user, $complaint),

           EnRoleType::CLINIC => $this->isComplaintBelongToThatClinicOwner($user, $complaint->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can respond to the complaintt.
     */
    public function respond(User $user, Complaint $complaint): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC => $this->isComplaintBelongToThatClinicOwner($user, $complaint->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can change the complaintt status.
     */
    public function changeStatus(User $user, Complaint $complaint): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC => $this->isComplaintBelongToThatClinicOwner($user, $complaint->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
