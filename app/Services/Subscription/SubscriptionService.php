<?php

namespace App\Services\Subscription;

use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Exceptions\ActiveSubscriptionAlreadyExistsException;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    public function __construct(private SubscriptionValidationService $subscriptionValidationService) {}
    public function add(array $data): Subscription
    {
        return DB::transaction(function () use ($data) {
            Clinic::where('id', $data['clinic_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $plan = Plan::select(['id', 'monthly_price'])
                ->whereStatus(PlanStatus::ACTIVE->value)
                ->findOrFail($data['plan_id']);

            if ($this->subscriptionValidationService->hasActiveSubscription($data['clinic_id'])) {
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
    public function renew(int $subscriptionId): bool
    {
        return DB::transaction(function () use ($subscriptionId) {

            $subscription = Subscription::lockForUpdate()
                ->select('id', 'clinic_id')
                ->findOrFail($subscriptionId);

            if ($this->subscriptionValidationService->hasActiveSubscription($subscription->clinic_id, $subscription->id)) {
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
 }
