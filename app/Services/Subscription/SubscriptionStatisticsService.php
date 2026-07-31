<?php

namespace App\Services;

use App\Models\Subscription;

class SubscriptionStatisticsService
{
    public function getStats(): Subscription
    {
        $today = now();
        $after7Days = $today->copy()->addDays(7);
        return Subscription::query()
            ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN status = 'expired' THEN 1 ELSE 0 END) as expired,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as inactive,
        SUM(CASE
            WHEN status = 'active'
             AND end_at BETWEEN ? AND ?
            THEN 1
            ELSE 0
        END) as expiring
    ", [$today->toDateString(), $after7Days->toDateString()])->first();
    }
    public function getMonthlyRevenue() {}
    public function getActiveSubscriptionsCount() {}
}
