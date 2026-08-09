<?php

namespace App\Policies;

use App\Enums\RoleType;
use App\Models\MedicalService as MedicalServiceModel;
use App\Models\User;

class MedicalService
{
    public function isOwnerOfTheService(User $user, int $clinicServiceId): bool
    {
        // return $user->clinics()
        //     ->servicePrices()
        //     ->doctorService()
        //     ->whereKey($clinicServiceId)->exists();

        return $user->clinics()
        ->whereHas('servicePrices.doctorService',
        fn($query)=>$query->whereKey($clinicServiceId))->exists();

    }

    /**
     * Determine whether the user can view any services.
     */
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
     * Determine whether the user can view the service.
     */
    public function view(User $user, MedicalServiceModel $clinicService): bool
    {
        return match ($user->role) {
            RoleType::PATIENT => true,

            RoleType::CLINIC => $this->isOwnerOfTheService($user, $clinicService->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a service.
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
     * Determine whether the user can update the service.
     */
    public function update(User $user, MedicalServiceModel $clinicService): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->isOwnerOfTheService($user, $clinicService->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the service.
     */
    public function delete(User $user, MedicalServiceModel $clinicService): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->isOwnerOfTheService($user, $clinicService->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
