<?php

namespace App\Services\Clinic;

use App\Enums\EnAppointmentStatus;
use App\Enums\EnSubscriptionStatus;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ClinicStatisticsService
{
    public function getStats(): array
    {
        return Cache::remember(
            'subscriptions.statistics',
            now()->addMinutes(10),
            function () {
                $statistics = Subscription::query()
                    ->selectRaw("
                    COUNT(*) as total,
                    COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as pending,
                    COALESCE(SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END), 0) as active,
                    COALESCE(
                        SUM(
                            CASE
                                WHEN status = 'cancelled'
                                OR status = 'expired'
                                THEN 1
                                ELSE 0
                            END
                        ),
                        0
                    ) as inactive
                ")
                    ->whereIn('id', function ($query) {
                        $query->selectRaw('MAX(id)')
                            ->from('subscriptions')
                            ->whereNotNull('clinic_id')
                            ->groupBy('clinic_id');
                    })
                    ->first();

                return [
                    'total' => (int) $statistics->total,
                    'pending' => (int) $statistics->pending,
                    'active' => (int) $statistics->active,
                    'inactive' => (int) $statistics->inactive,
                ];
            }
        );
    }

    public function getClinicDashboardStats(): array
    {
        return Cache::remember(
            'clinic.dashboard.stats',
            now()->addMinutes(5),
            function () {
                $statistics = [
                    'users_total' => User::whereType('patient')->count(),
                    'clinics_total' => Clinic::count(),
                    'appointments_total' => Appointment::count(),
                    'earnings_total' => Subscription::sum('price'),

                    'active_subscriptions' => Subscription::whereStatus(
                        EnSubscriptionStatus::ACTIVE->value
                    )->count(),

                    'cancelled_appointments' => Appointment::whereStatus(
                        EnAppointmentStatus::CANCELLED->value
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
        );
    }
}
