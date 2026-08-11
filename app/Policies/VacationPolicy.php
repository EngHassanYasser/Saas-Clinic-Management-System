<?php

namespace App\Policies;

use App\Enums\EnRoleType;
use App\Models\User;
use App\Models\Vacation;

class VacationPolicy
{
    private function belongsToClinicOwner(User $user, int $vacationId): bool
    {
        return $user->clinics()
            ->whereHas('vacations',
                fn ($query) => $query->whereKey($vacationId))
            ->exists();
    }

    /**
     * Determine whether the user can view any vacations.
     */
    public function viewAny(User $user): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC,
           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can view the vacation.
     */
    public function view(User $user, Vacation $vacation): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC =>$this->belongsToClinicOwner($user,$vacation->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a vacation.
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
     * Determine whether the user can update the vacation.
     */
    public function update(User $user, Vacation $vacation): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC =>$this->belongsToClinicOwner($user,$vacation->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the vacation.
     */
    public function delete(User $user, Vacation $vacation): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC =>$this->belongsToClinicOwner($user,$vacation->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
