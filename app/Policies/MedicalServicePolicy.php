<?php

namespace App\Policies;

use App\Enums\EnRoleType;
use App\Models\Medical_service;
use App\Models\User;

class Medical_servicePolicy
{
    public function isOwnerOfTheService(User $user, int $clinicServiceId): bool
    {
        // return $user->clinics()
        //     ->servicePrices()
        //     ->medicalService()
        //     ->whereKey($clinicServiceId)->exists();

        return $user->clinics()
        ->whereHas('servicePrices.medicalService',
        fn($query)=>$query->whereKey($clinicServiceId))->exists();

    }

    /**
     * Determine whether the user can view any services.
     */
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
     * Determine whether the user can view the service.
     */
    public function view(User $user, Medical_service $clinicService): bool
    {
        return match ($user->type) {
           EnRoleType::PATIENT => true,

           EnRoleType::CLINIC => $this->isOwnerOfTheService($user, $clinicService->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a service.
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
     * Determine whether the user can update the service.
     */
    public function update(User $user, Medical_service $clinicService): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC => $this->isOwnerOfTheService($user, $clinicService->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the service.
     */
    public function delete(User $user, Medical_service $clinicService): bool
    {
        return match ($user->type) {
           EnRoleType::CLINIC => $this->isOwnerOfTheService($user, $clinicService->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
