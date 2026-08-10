<?php

namespace App\Services\Subscription;

use App\Enums\EnPlanStatus;
use App\Enums\EnSubscriptionStatus;
use App\Exceptions\ActiveSubscriptionAlreadyExistsException;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function __construct(private SubscriptionValidationService $subscriptionValidationService) {}
    public function add(int $clinicId,int $planId): Subscription
    {
        return DB::transaction(function () use ($clinicId,$planId) {
            Clinic::where('id', $clinicId)
                ->lockForUpdate()
                ->firstOrFail();

            $plan = Plan::select(['id', 'monthly_price'])
                ->whereStatus(EnPlanStatus::ACTIVE->value)
                ->findOrFail($planId);

            if ($this->subscriptionValidationService->hasActiveSubscription($clinicId)) {
                throw new ActiveSubscriptionAlreadyExistsException();
            }

            $startAt = now();
            return Subscription::create([
                'start_at' => $startAt->toDateString(),
                'end_at'   => $startAt->copy()->addMonth()->toDateString(),
                'price' => $plan->monthly_price,
                'clinic_id' => $clinicId,
                'plan_id' => $plan->id,
            ]);
        });
    }
    public function renew(int $subscriptionId): bool
    {
        return DB::transaction(function () use ($subscriptionId) {

            $subscription = Subscription::lockForUpdate()
                ->select('id', 'clinic_id')
                ->findOrFail($subscriptionId);

            if ($this->subscriptionValidationService->hasActiveSubscription($subscription->clinic_id, $subscription->id)) {
                throw new ActiveSubscriptionAlreadyExistsException();
            }

            $startAt = now()->utc();

            return $subscription->update([
                'start_at' => $startAt->toDateString(),
                'end_at'   => $startAt->copy()->addMonth()->toDateString(),
                'status'   => EnSubscriptionStatus::ACTIVE->value,
            ]);
        });
    }
 }
