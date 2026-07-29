<?php

namespace App\Services;

use App\Enums\AppointmentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\activity_log;
use App\Models\appointment;
use App\Models\clinic;
use App\Models\subscription;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardService
{
    public function getClinicDashboardStats(): array
    {
        $stats = [
            'users_total' =>   User::whereType('patient')->count(),
            'clinics_total' => clinic::count(),
            'appointments_total' => appointment::count(),
            'subscriptions_total' => subscription::sum('price'),
            'active_subscriptions' => subscription::whereStatus(SubscriptionStatus::ACTIVE->value)->count(),
            'cancelled_appointments' => appointment::whereStatus(AppointmentStatus::CANCELLED->value)->count(),
        ];
        return $stats;
    }
    public function getLastActivities(): LengthAwarePaginator
    {
        return activity_log::select([
            'id',
            'type',
            'title',
            'description',
            'status',
            'subject_type',
            'subject_id',
            'created_by',
            'created_at',
        ])->paginate(5);
    }
}
