<?php

use App\Enums\EnSubscriptionStatus;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Subscription\EnSubscriptionStatusService;
beforeEach(function () {
    $this->service = app(EnSubscriptionStatusService::class);
});


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function createChangeStatusSubscription(array $overrides = []): Subscription
{
    return Subscription::factory()->create(array_merge([
        'clinic_id' => Clinic::factory()->create()->id,
        'plan_id' => Plan::factory()->create()->id,
        'status' => EnSubscriptionStatus::PENDING,
        'start_at' => now()->toDateString(),
        'end_at' => now()->addMonth()->toDateString(),
    ], $overrides));
}


/*
|--------------------------------------------------------------------------
| Return value
|--------------------------------------------------------------------------
*/


it('returns true when subscription status is changed successfully', function () {

    $subscription = createChangeStatusSubscription();

    $result = $this->service->changeStatus(
        $subscription->id,
        EnSubscriptionStatus::ACTIVE
    );

    expect($result)
        ->toBeTrue();
});


it('returns false when subscription does not exist', function () {

    $result = $this->service->changeStatus(
        999999,
        EnSubscriptionStatus::ACTIVE
    );

    expect($result)
        ->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Database update
|--------------------------------------------------------------------------
*/


it('updates the subscription status in database', function () {

    $subscription = createChangeStatusSubscription([
        'status' => EnSubscriptionStatus::PENDING,
    ]);


    $this->service->changeStatus(
        $subscription->id,
        EnSubscriptionStatus::ACTIVE
    );


    $subscription->refresh();


    expect($subscription->status)
        ->toBe(EnSubscriptionStatus::ACTIVE);
});


it('stores all enum statuses correctly', function () {

    foreach (EnSubscriptionStatus::cases() as $status) {

        $subscription = createChangeStatusSubscription();


        $result = $this->service->changeStatus(
            $subscription->id,
            $status
        );


        expect($result)
            ->toBeTrue();


        $subscription->refresh();


        expect($subscription->status)
            ->toBe($status);
    }
});


/*
|--------------------------------------------------------------------------
| Existing data preservation
|--------------------------------------------------------------------------
*/


it('does not modify other subscription fields', function () {

    $subscription = createChangeStatusSubscription([
        'status' => EnSubscriptionStatus::PENDING,
    ]);


    $original = [
        'clinic_id' => $subscription->clinic_id,
        'plan_id' => $subscription->plan_id,
        'start_at' => $subscription->start_at,
        'end_at' => $subscription->end_at,
    ];


    $this->service->changeStatus(
        $subscription->id,
        EnSubscriptionStatus::ACTIVE
    );


    $subscription->refresh();


    expect($subscription->clinic_id)
        ->toBe($original['clinic_id']);

    expect($subscription->plan_id)
        ->toBe($original['plan_id']);

    expect($subscription->start_at)
        ->toBe($original['start_at']);

    expect($subscription->end_at)
        ->toBe($original['end_at']);
});


it('does not create a new subscription when changing status', function () {

    $subscription = createChangeStatusSubscription();


    $before = Subscription::count();


    $this->service->changeStatus(
        $subscription->id,
        EnSubscriptionStatus::ACTIVE
    );


    expect(Subscription::count())
        ->toBe($before);
});


/*
|--------------------------------------------------------------------------
| Same status
|--------------------------------------------------------------------------
*/


it('can update to the same current status', function () {

    $subscription = createChangeStatusSubscription([
        'status' => EnSubscriptionStatus::ACTIVE,
    ]);


    $result = $this->service->changeStatus(
        $subscription->id,
        EnSubscriptionStatus::ACTIVE
    );


    expect($result)
        ->toBeTrue();


    expect($subscription->fresh()->status)
        ->toBe(EnSubscriptionStatus::ACTIVE);
});


/*
|--------------------------------------------------------------------------
| Different transitions
|--------------------------------------------------------------------------
*/


it('can change pending subscription to active', function () {

    $subscription = createChangeStatusSubscription([
        'status' => EnSubscriptionStatus::PENDING,
    ]);


    $this->service->changeStatus(
        $subscription->id,
        EnSubscriptionStatus::ACTIVE
    );


    expect($subscription->fresh()->status)
        ->toBe(EnSubscriptionStatus::ACTIVE);
});


it('can change active subscription to expired', function () {

    $subscription = createChangeStatusSubscription([
        'status' => EnSubscriptionStatus::ACTIVE,
    ]);


    $this->service->changeStatus(
        $subscription->id,
        EnSubscriptionStatus::EXPIRED
    );


    expect($subscription->fresh()->status)
        ->toBe(EnSubscriptionStatus::EXPIRED);
});


it('can change active subscription to cancelled', function () {

    $subscription = createChangeStatusSubscription([
        'status' => EnSubscriptionStatus::ACTIVE,
    ]);


    $this->service->changeStatus(
        $subscription->id,
        EnSubscriptionStatus::CANCELLED
    );


    expect($subscription->fresh()->status)
        ->toBe(EnSubscriptionStatus::CANCELLED);
});


/*
|--------------------------------------------------------------------------
| Multiple records isolation
|--------------------------------------------------------------------------
*/


it('only changes the requested subscription', function () {

    $first = createChangeStatusSubscription([
        'status' => EnSubscriptionStatus::PENDING,
    ]);


    $second = createChangeStatusSubscription([
        'status' => EnSubscriptionStatus::PENDING,
    ]);


    $this->service->changeStatus(
        $first->id,
        EnSubscriptionStatus::ACTIVE
    );


    expect($first->fresh()->status)
        ->toBe(EnSubscriptionStatus::ACTIVE);


    expect($second->fresh()->status)
        ->toBe(EnSubscriptionStatus::PENDING);
});


/*
|--------------------------------------------------------------------------
| Persistence
|--------------------------------------------------------------------------
*/


it('persists the status change after reloading model', function () {

    $subscription = createChangeStatusSubscription();


    $this->service->changeStatus(
        $subscription->id,
        EnSubscriptionStatus::CANCELLED
    );


    $fresh = Subscription::find($subscription->id);


    expect($fresh->status)
        ->toBe(EnSubscriptionStatus::CANCELLED);
});


/*
|--------------------------------------------------------------------------
| Large dataset
|--------------------------------------------------------------------------
*/


it('changes status correctly when many subscriptions exist', function () {

    $target = createChangeStatusSubscription();


    Subscription::factory()
        ->count(50)
        ->create([
            'clinic_id' => Clinic::factory(),
            'plan_id' => Plan::factory(),
            'status' => EnSubscriptionStatus::PENDING,
        ]);


    $result = $this->service->changeStatus(
        $target->id,
        EnSubscriptionStatus::ACTIVE
    );


    expect($result)
        ->toBeTrue();


    expect($target->fresh()->status)
        ->toBe(EnSubscriptionStatus::ACTIVE);
});