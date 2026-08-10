<?php

namespace App\Policies;

use App\Enums\EnRoleType;
use App\Models\Subscription;
use App\Models\User;

class SubscriptionPolicy
{
    private function belongsToClinicOwner(User $user, int $subscriptionId): bool
    {
        return $user->clinics()->whereHas('subscriptions',
            fn ($query) => $query->whereKey($subscriptionId))->exists();
    }

    /**
     * Determine whether the user can view any subscriptions.
     */
    public function viewAny(User $user): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC,
           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can view the subscription.
     */
    public function view(User $user, Subscription $subscription): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC => $this->belongsToClinicOwner($user, $subscription->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a subscription.
     */
    public function create(User $user): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC,
           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can update the subscription.
     */
    public function update(User $user, Subscription $subscription): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC => $this->belongsToClinicOwner($user, $subscription->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can cancel the subscription.
     */
    public function cancel(User $user, Subscription $subscription): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC => $this->belongsToClinicOwner($user, $subscription->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can renew the subscription.
     */
    public function renew(User $user, Subscription $subscription): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC => $this->belongsToClinicOwner($user, $subscription->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can change the subscription plan.
     */
    public function changePlan(User $user, Subscription $subscription): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC => $this->belongsToClinicOwner($user, $subscription->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
