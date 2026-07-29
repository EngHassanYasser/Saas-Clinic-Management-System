<?php

namespace App\services;

use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Exceptions\ActiveSubscriptionAlreadyExistsException;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\subscription;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function getAll():Collection
    {
        return Subscription::select([
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
    public function getStats():Subscription 
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
    public function changeStatus(
        int $subscriptionId,
        SubscriptionStatus $newStatus
    ): bool {
        return Subscription::whereKey($subscriptionId)
            ->update([
                'status' => $newStatus,
            ]) > 0;
    }
    public function renew(int $subscriptionId): bool
    {
        return DB::transaction(function () use ($subscriptionId) {

            $subscription = Subscription::lockForUpdate()
                ->select('id', 'clinic_id')
                ->findOrFail($subscriptionId);

            if ($this->hasActiveSubscription($subscription->clinic_id, $subscription->id)) {
                throw new ActiveSubscriptionAlreadyExistsException();
            }

            $startAt = now();

            return $subscription->update([
                'start_at' => $startAt->toDateString(),
                'end_at'   => $startAt->copy()->addMonth()->toDateString(),
                'status'   => SubscriptionStatus::ACTIVE->value,
            ]);
        });
    }
    public function add(array $data): Subscription
    {
        return DB::transaction(function () use ($data) {
            Clinic::where('id', $data['clinic_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $plan = plan::select(['id', 'monthly_price'])
                ->whereStatus(PlanStatus::ACTIVE->value)
                ->findOrFail($data['plan_id']);

            if ($this->hasActiveSubscription($data['clinic_id'])) {
                throw new ActiveSubscriptionAlreadyExistsException();
            }

            $startAt = now();
            return Subscription::create([
                'start_at' => $startAt->toDateString(),
                'end_at'   => $startAt->copy()->addMonth()->toDateString(),
                'price' => $plan->monthly_price,
                'clinic_id' => $data['clinic_id'],
                'plan_id' => $plan->id,
            ]);
        });
    }
    public function hasActiveSubscription(
        int $clinicId,
        ?int $ignoreSubscriptionId = 0
    ): bool {
        return Subscription::where('clinic_id', $clinicId)
            ->where('status', SubscriptionStatus::ACTIVE)
            ->when(
                $ignoreSubscriptionId,
                fn($query) => $query->whereKeyNot($ignoreSubscriptionId)
            )
            ->exists();
    }
}
