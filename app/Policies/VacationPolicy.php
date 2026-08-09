<?php

namespace App\Policies;

use App\Enums\RoleType;
use App\Models\User;
use App\Models\Vication;

class VicationPolicy
{
    private function belongsToClinicOwner(User $user, int $vicationId): bool
    {
        return $user->clinics()
            ->whereHas('vications',
                fn ($query) => $query->whereKey($vicationId))
            ->exists();
    }

    /**
     * Determine whether the user can view any vications.
     */
    public function viewAny(User $user): bool
    {
        return match ($user->role) {
            RoleType::CLINIC,
            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can view the vication.
     */
    public function view(User $user, Vication $vication): bool
    {
        return match ($user->role) {
            RoleType::CLINIC =>$this->belongsToClinicOwner($user,$vication->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a vication.
     */
    public function create(User $user): bool
    {
        return match ($user->role) {
            RoleType::CLINIC,
            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can update the vication.
     */
    public function update(User $user, Vication $vication): bool
    {
        return match ($user->role) {
            RoleType::CLINIC =>$this->belongsToClinicOwner($user,$vication->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the vication.
     */
    public function delete(User $user, Vication $vication): bool
    {
        return match ($user->role) {
            RoleType::CLINIC =>$this->belongsToClinicOwner($user,$vication->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
