<?php

use App\Enums\EnSubscriptionStatus;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionValidationService;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);


beforeEach(function () {
    $this->service = app(SubscriptionValidationService::class);
});


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function createActiveCheckSubscription(array $overrides = []): Subscription
{
    return Subscription::factory()->create(array_merge([
        'clinic_id' => Clinic::factory()->create()->id,
        'plan_id' => Plan::factory()->create()->id,
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'start_at' => now()->subMonth()->toDateString(),
        'end_at' => now()->addMonth()->toDateString(),
    ], $overrides));
}


/*
|--------------------------------------------------------------------------
| Empty database
|--------------------------------------------------------------------------
*/

it('returns false when clinic has no subscriptions', function () {

    $clinic = Clinic::factory()->create();


    $result = $this->service->hasActiveSubscription(
        $clinic->id
    );


    expect($result)
        ->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Active subscription exists
|--------------------------------------------------------------------------
*/

it('returns true when clinic has an active subscription', function () {

    $subscription = createActiveCheckSubscription();


    $result = $this->service->hasActiveSubscription(
        $subscription->clinic_id
    );


    expect($result)
        ->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Status filtering
|--------------------------------------------------------------------------
*/

it('does not count pending subscriptions as active', function () {

    $subscription = createActiveCheckSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);


    $result = $this->service->hasActiveSubscription(
        $subscription->clinic_id
    );


    expect($result)
        ->toBeFalse();
});


it('does not count expired subscriptions as active', function () {

    $subscription = createActiveCheckSubscription([
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);


    $result = $this->service->hasActiveSubscription(
        $subscription->clinic_id
    );


    expect($result)
        ->toBeFalse();
});


it('does not count cancelled subscriptions as active', function () {

    $subscription = createActiveCheckSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);


    $result = $this->service->hasActiveSubscription(
        $subscription->clinic_id
    );


    expect($result)
        ->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Ignore subscription
|--------------------------------------------------------------------------
*/

it('ignores the provided subscription id', function () {

    $subscription = createActiveCheckSubscription();


    $result = $this->service->hasActiveSubscription(
        $subscription->clinic_id,
        $subscription->id
    );


    expect($result)
        ->toBeFalse();
});


it('returns true when another active subscription exists after ignoring one', function () {

    $clinic = Clinic::factory()->create();

    $first = createActiveCheckSubscription([
        'clinic_id' => $clinic->id,
    ]);


    $second = createActiveCheckSubscription([
        'clinic_id' => $clinic->id,
    ]);


    $result = $this->service->hasActiveSubscription(
        $clinic->id,
        $first->id
    );


    expect($result)
        ->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Multiple clinics isolation
|--------------------------------------------------------------------------
*/

it('does not check subscriptions from other clinics', function () {

    $clinicOne = Clinic::factory()->create();

    $clinicTwo = Clinic::factory()->create();


    createActiveCheckSubscription([
        'clinic_id' => $clinicOne->id,
    ]);


    $result = $this->service->hasActiveSubscription(
        $clinicTwo->id
    );


    expect($result)
        ->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Multiple statuses
|--------------------------------------------------------------------------
*/

it('returns true when clinic has mixed subscriptions including active', function () {

    $clinic = Clinic::factory()->create();


    createActiveCheckSubscription([
        'clinic_id' => $clinic->id,
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);


    createActiveCheckSubscription([
        'clinic_id' => $clinic->id,
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);


    createActiveCheckSubscription([
        'clinic_id' => $clinic->id,
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);


    $result = $this->service->hasActiveSubscription(
        $clinic->id
    );


    expect($result)
        ->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Large dataset
|--------------------------------------------------------------------------
*/

it('finds active subscription correctly with many subscriptions', function () {

    $clinic = Clinic::factory()->create();


    Subscription::factory()
        ->count(50)
        ->create([
            'clinic_id' => $clinic->id,
            'plan_id' => Plan::factory(),
            'status' => EnSubscriptionStatus::PENDING->value,
        ]);


    createActiveCheckSubscription([
        'clinic_id' => $clinic->id,
    ]);


    $result = $this->service->hasActiveSubscription(
        $clinic->id
    );


    expect($result)
        ->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Different ignored ids
|--------------------------------------------------------------------------
*/

it('does not ignore another clinic subscription', function () {

    $clinicOne = Clinic::factory()->create();

    $clinicTwo = Clinic::factory()->create();


    $subscription = createActiveCheckSubscription([
        'clinic_id' => $clinicOne->id,
    ]);


    createActiveCheckSubscription([
        'clinic_id' => $clinicTwo->id,
    ]);


    $result = $this->service->hasActiveSubscription(
        $clinicOne->id,
        $subscription->id
    );


    expect($result)
        ->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Database unchanged
|--------------------------------------------------------------------------
*/

it('does not modify subscriptions', function () {

    $subscription = createActiveCheckSubscription();


    $before = Subscription::count();


    $this->service->hasActiveSubscription(
        $subscription->clinic_id
    );


    expect(Subscription::count())
        ->toBe($before);
});


/*
|--------------------------------------------------------------------------
| Non existing clinic
|--------------------------------------------------------------------------
*/

it('returns false for non existing clinic id', function () {

    $result = $this->service->hasActiveSubscription(
        999999
    );


    expect($result)
        ->toBeFalse();
});