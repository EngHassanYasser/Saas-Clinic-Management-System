<?php

namespace App\Services;

use App\Models\Subscription;

class ClinicStatisticsService
{
    public function getStats(): Subscription
    {
        return Subscription::query()
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
    }
}
