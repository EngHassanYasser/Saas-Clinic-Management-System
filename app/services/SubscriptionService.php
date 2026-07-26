<?php

namespace App\services;

use App\Models\plan;
use App\Models\subscription;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function getAll()
    {
        return subscription::select([
            'id',
            'start_at',
            'end_at',
            'status',
            'price',
            'auto_renew',
            'clinic_id',
            'plan_id'
        ])->with(['plan:id,name,monthly_price', 'clinic:id,name'])
            ->get()->makeHidden(['clinic_id', 'plan_id']);
    }
    public function getStats()
    {
        $today = now();
        $after7Days = now()->addDays(7);

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
    ", [$today, $after7Days])
            ->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('subscriptions')
                    ->groupBy('clinic_id');
            })->first();
    }
    public function changeStatus($subscriptionID, $newStatus): bool
    {
        return Subscription::whereKey($subscriptionID)
            ->update([
                'status' => $newStatus,
            ]);
    }
    public function renew(int $subscriptionID): bool
    {
        return Subscription::whereKey($subscriptionID)
            ->update([
                'start_at' => now(),
                'end_at'   => now()->addDays(30),
                'status'   => 'active',
            ]);
    }
    public function add($data)
    {
        return DB::transaction(function () use ($data) {
            $plan = plan::get(['id', 'monthly_price'])
                ->findOrFail($data['plan_id']);

            return subscription::create([
                'start_at' => now()->toDateString(),
                'end_at' => now()->addMonth(),
                'price' => $plan->monthly_price,
                'clinic_id' => $data['clinic_id'],
                'plan_id' => $plan->id,
            ]);
        });
    }
}
