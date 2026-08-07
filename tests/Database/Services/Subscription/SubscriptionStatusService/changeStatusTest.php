<?php

use App\Enums\SubscriptionStatus;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionStatusService;
beforeEach(function () {
    $this->service = app(SubscriptionStatusService::class);
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
        'status' => SubscriptionStatus::PENDING,
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
        SubscriptionStatus::ACTIVE
    );

    expect($result)
        ->toBeTrue();
});


it('returns false when subscription does not exist', function () {

    $result = $this->service->changeStatus(
        999999,
        SubscriptionStatus::ACTIVE
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
        'status' => SubscriptionStatus::PENDING,
    ]);


    $this->service->changeStatus(
        $subscription->id,
        SubscriptionStatus::ACTIVE
    );


    $subscription->refresh();


    expect($subscription->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});


it('stores all enum statuses correctly', function () {

    foreach (SubscriptionStatus::cases() as $status) {

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
        'status' => SubscriptionStatus::PENDING,
    ]);


    $original = [
        'clinic_id' => $subscription->clinic_id,
        'plan_id' => $subscription->plan_id,
        'start_at' => $subscription->start_at,
        'end_at' => $subscription->end_at,
    ];


    $this->service->changeStatus(
        $subscription->id,
        SubscriptionStatus::ACTIVE
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
        SubscriptionStatus::ACTIVE
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
        'status' => SubscriptionStatus::ACTIVE,
    ]);


    $result = $this->service->changeStatus(
        $subscription->id,
        SubscriptionStatus::ACTIVE
    );


    expect($result)
        ->toBeTrue();


    expect($subscription->fresh()->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});


/*
|--------------------------------------------------------------------------
| Different transitions
|--------------------------------------------------------------------------
*/


it('can change pending subscription to active', function () {

    $subscription = createChangeStatusSubscription([
        'status' => SubscriptionStatus::PENDING,
    ]);


    $this->service->changeStatus(
        $subscription->id,
        SubscriptionStatus::ACTIVE
    );


    expect($subscription->fresh()->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});


it('can change active subscription to expired', function () {

    $subscription = createChangeStatusSubscription([
        'status' => SubscriptionStatus::ACTIVE,
    ]);


    $this->service->changeStatus(
        $subscription->id,
        SubscriptionStatus::EXPIRED
    );


    expect($subscription->fresh()->status)
        ->toBe(SubscriptionStatus::EXPIRED);
});


it('can change active subscription to cancelled', function () {

    $subscription = createChangeStatusSubscription([
        'status' => SubscriptionStatus::ACTIVE,
    ]);


    $this->service->changeStatus(
        $subscription->id,
        SubscriptionStatus::CANCELLED
    );


    expect($subscription->fresh()->status)
        ->toBe(SubscriptionStatus::CANCELLED);
});


/*
|--------------------------------------------------------------------------
| Multiple records isolation
|--------------------------------------------------------------------------
*/


it('only changes the requested subscription', function () {

    $first = createChangeStatusSubscription([
        'status' => SubscriptionStatus::PENDING,
    ]);


    $second = createChangeStatusSubscription([
        'status' => SubscriptionStatus::PENDING,
    ]);


    $this->service->changeStatus(
        $first->id,
        SubscriptionStatus::ACTIVE
    );


    expect($first->fresh()->status)
        ->toBe(SubscriptionStatus::ACTIVE);


    expect($second->fresh()->status)
        ->toBe(SubscriptionStatus::PENDING);
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
        SubscriptionStatus::CANCELLED
    );


    $fresh = Subscription::find($subscription->id);


    expect($fresh->status)
        ->toBe(SubscriptionStatus::CANCELLED);
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
            'status' => SubscriptionStatus::PENDING,
        ]);


    $result = $this->service->changeStatus(
        $target->id,
        SubscriptionStatus::ACTIVE
    );


    expect($result)
        ->toBeTrue();


    expect($target->fresh()->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});