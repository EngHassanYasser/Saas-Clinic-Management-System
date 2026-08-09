<?php

namespace App\Policies;

use App\Enums\RoleType;
use App\Models\Payment;
use App\Models\User;

class PaymentPolicy
{
    /**
     * Determine whether the user can view any payments.
     */
    private function belongsToPatient(User $user, int $paymentId): bool
    {
        return $user->appointments()
            ->whereHas('payments', fn ($query) => $query->whereKey($paymentId)
            )->exists();
    }

    private function belongsToClinicOwner(
        User $user,
        int $paymentId
    ): bool {
        return $user->clinics()
            ->whereHas(
                'appointments.payments',
                fn ($query) => $query->whereKey($paymentId)
            )->exists();
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
     * Determine whether the user can view the payment.
     */
    public function view(User $user, Payment $payment): bool
    {
        return match ($user->role) {
            RoleType::PATIENT => $this->belongsToPatient($user, $payment->id),

            RoleType::CLINIC => $this->belongsToClinicOwner($user, $payment->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a payment.
     */
    public function create(User $user): bool
    {
        return match ($user->role) {
            RoleType::PATIENT,
            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can update the payment.
     */
    public function update(User $user, Payment $payment): bool
    {
        return match ($user->role) {
            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the payment.
     */
    public function delete(User $user, Payment $payment): bool
    {
        return $user->role === RoleType::SUPER_ADMIN;
    }

    /**
     * Determine whether the user can refund the payment.
     */
    public function refund(User $user, Payment $payment): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->belongsToClinicOwner($user, $payment->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
