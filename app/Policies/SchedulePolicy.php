<?php

namespace App\Policies;

use App\Enums\EnRoleType;
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
           EnRoleType::PATIENT,
           EnRoleType::CLINIC,
           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can view the schedule.
     */
    public function view(User $user, Schedule $schedule): bool
    {
        return match ($user->role) {
           EnRoleType::PATIENT => true,
           EnRoleType::CLINIC => $this->belogsToClinicOwner($user, $schedule->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can create a schedule.
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
     * Determine whether the user can update the schedule.
     */
    public function update(User $user, Schedule $schedule): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC => $this->belogsToClinicOwner($user, $schedule->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }

    /**
     * Determine whether the user can delete the schedule.
     */
    public function delete(User $user, Schedule $schedule): bool
    {
        return match ($user->role) {
           EnRoleType::CLINIC => $this->belogsToClinicOwner($user, $schedule->id),

           EnRoleType::SUPER_ADMIN => true,

            default => false,
        };
    }
}
