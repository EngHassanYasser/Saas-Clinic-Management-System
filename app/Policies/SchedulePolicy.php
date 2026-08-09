<?php

namespace App\Policies;

use App\Enums\RoleType;
use App\Models\Schedule;
use App\Models\User;

class SchedulePolicy
{
    private function belogsToClinicOwner(User $user, int $scheduleId): bool
    {
        return $user->clinics()
            ->whereHas('schedules',
                fn ($query) => $query->whereKey($scheduleId))->exists();
    }

    /**
     * Determine whether the user can view any schedules.
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
     * Determine whether the user can view the schedule.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        return match ($user->role) {
            RoleType::PATIENT => true,
            RoleType::CLINIC => $this->belogsToClinicOwner($user, $schedule->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a schedule.
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
     * Determine whether the user can update the schedule.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->belogsToClinicOwner($user, $schedule->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the schedule.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        return match ($user->role) {
            RoleType::CLINIC => $this->belogsToClinicOwner($user, $schedule->id),

            RoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
