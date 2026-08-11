<?php

namespace App\Policies;

use App\Enums\EnRoleType;
use App\Models\Plan;
use App\Models\User;

class PlanPolicy
{
    /**
     * Determine whether the user can view any plans.
     */
    public function viewAny(User $user): bool
    {
        return match ($user->type) {
            EnRoleType::SUPER_ADMIN,
            EnRoleType::CLINIC,
            EnRoleType::PATIENT => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can view the plan.
     */
    public function view(User $user, Plan $plan): bool
    {
        return match ($user->type) {
            EnRoleType::SUPER_ADMIN,
            EnRoleType::CLINIC,
            EnRoleType::PATIENT => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a plan.
     */
    public function create(User $user): bool
    {
        return $user->type === EnRoleType::SUPER_ADMIN;
    }

    /**
     * Determine whether the user can update the plan.
     */
    public function update(User $user, Plan $plan): bool
    {
        return $user->type === EnRoleType::SUPER_ADMIN;
    }

    /**
     * Determine whether the user can delete the plan.
     */
    public function delete(User $user, Plan $plan): bool
    {
        return $user->type === EnRoleType::SUPER_ADMIN;
    }
}