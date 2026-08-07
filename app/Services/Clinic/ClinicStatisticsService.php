<?php

namespace App\Services\Clinic;

use App\Models\Subscription;
use App\Enums\AppointmentStatus;
use App\Enums\SubscriptionStatus;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
class ClinicStatisticsService
{
    public function getStats(): Subscription
    {
        $statistics = Subscription::query()
            ->selectRaw("
        COUNT(*) as total,
        COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as pending,
        COALESCE(SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END), 0) as active,
        COALESCE(SUM(CASE WHEN status = 'cancelled' OR status = 'expired' THEN 1 ELSE 0 END),0) AS inactive    ")->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('subscriptions')
                    ->whereNotNull('clinic_id')
                    ->groupBy('clinic_id');
            })->first();
        foreach (['total', 'pending', 'active', 'inactive'] as $field) {
            $statistics->{$field} = (int) $statistics->{$field};
        }
          return $statistics;
    }
      public function getClinicDashboardStats(): array
    {
        $statistics = [
            'users_total' => User::whereType('patient')->count(),
            'clinics_total' => Clinic::count(),
            'appointments_total' => Appointment::count(),
            'earnings_total' => Subscription::sum('price'),

            'active_subscriptions' => Subscription::whereStatus(
                SubscriptionStatus::ACTIVE->value
            )->count(),

            'cancelled_appointments' => Appointment::whereStatus(
                AppointmentStatus::CANCELLED->value
            )->count(),
        ];

        foreach ([
            'users_total',
            'clinics_total',
            'appointments_total',
            'active_subscriptions',
            'cancelled_appointments',
        ] as $field) {
            $statistics[$field] = (int) $statistics[$field];
        }

        $statistics['earnings_total'] = (float) $statistics['earnings_total'];
        return $statistics;
    }
}
