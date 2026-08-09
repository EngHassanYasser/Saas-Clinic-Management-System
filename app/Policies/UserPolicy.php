<?php

namespace App\Policies;

use App\Enums\RoleType;
use App\Models\Clinic;
use App\Models\User;

class UserPolicy
{
    private function belongsToUser(User $user, User $model): bool
    {
        return $user->id == $model->id;
    }

    private function belongsToClinic(User $user, User $model): bool
    {
        if ($user->id != $model->id) {
            return false;
        }

        return Clinic::where('owner_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can view any users.
     */
    public function viewAny(User $user): bool
    {
        return match ($user->role) {
            RoleType::PATIENT,
            RoleType::SUPER_ADMIN,
            RoleType::CLINIC => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can view the user.
     */
    public function view(User $user, User $model): bool
    {
        return match ($user->role) {
            RoleType::PATIENT => $this->belongsToUser($user, $model),
            RoleType::CLINIC => $this->belongsToClinic($user, $model->id),
            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create users.
     */
    public function create(User $user): bool
    {
        return match ($user->role) {
            RoleType::SUPER_ADMIN,
            RoleType::CLINIC => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can update the user.
     */
    public function update(User $user, User $model): bool
    {

        return match ($user->role) {
            RoleType::PATIENT => $this->belongsToUser($user, $model),
            RoleType::CLINIC => $this->belongsToClinic($user, $model->id),
            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the user.
     */
    public function delete(User $user, User $model): bool
    {

        return match ($user->role) {
            RoleType::PATIENT => $this->belongsToUser($user, $model),
            RoleType::CLINIC => $this->belongsToClinic($user, $model->id),
            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
