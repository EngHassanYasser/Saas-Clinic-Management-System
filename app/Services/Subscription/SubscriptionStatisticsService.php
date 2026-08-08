<?php

namespace App\Services\Subscription;

use App\Models\Subscription;
use Illuminate\Support\Facades\Cache;

class SubscriptionStatisticsService
{
    public function getStats(): array
    {
        return Cache::remember(
            'subscriptions.statistics',
            now()->addMinutes(5),
            function () {
                $today = now();
                $after7Days = $today->copy()->addDays(7);

                $statistics = Subscription::query()
                    ->selectRaw("
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
                    SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) as expired,
                    SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
                    SUM(CASE
                        WHEN status IN ('cancelled', 'expired')
                        THEN 1
                        ELSE 0
                    END) as inactive,
                    SUM(CASE
                        WHEN status = 'active'
                         AND end_at BETWEEN ? AND ?
                        THEN 1
                        ELSE 0
                    END) as expiring
                ", [
                        $today->toDateString(),
                        $after7Days->toDateString(),
                    ])
                    ->first();

                return [
                    'total' => (int) $statistics->total,
                    'pending' => (int) $statistics->pending,
                    'active' => (int) $statistics->active,
                    'expired' => (int) $statistics->expired,
                    'cancelled' => (int) $statistics->cancelled,
                    'inactive' => (int) $statistics->inactive,
                    'expiring' => (int) $statistics->expiring,
                ];
            }
        );
    }

    public function getMonthlyRevenue() {}

    public function getActiveSubscriptionsCount() {}
}
