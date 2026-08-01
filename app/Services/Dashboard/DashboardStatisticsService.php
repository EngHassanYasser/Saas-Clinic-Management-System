<?php

namespace App\Services\Dashboard;

use App\Enums\AppointmentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Activity_log;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class DashboardStatisticsService
{
    public function getClinicDashboardStats(): array
    {
        $stats = [
            'users_total' =>   User::whereType('patient')->count(),
            'clinics_total' => Clinic::count(),
            'appointments_total' => Appointment::count(),
            'earnings_total' => Subscription::sum('price'),
            'active_subscriptions' => Subscription::whereStatus(SubscriptionStatus::ACTIVE->value)->count(),
            'cancelled_appointments' => Appointment::whereStatus(AppointmentStatus::CANCELLED->value)->count(),
        ];
        return $stats;
    }
    public function getLastActivities(): LengthAwarePaginator
    {
        return Activity_log::select([
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
