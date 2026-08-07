<?php

use App\Enums\SubscriptionStatus;
use App\Models\City;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\Clinic\ClinicQueryService;
use Illuminate\Pagination\LengthAwarePaginator;

it('returns paginated clinics with transformed data', function () {

    $owner = User::factory()->clinic()->create([
        'name' => 'Ahmed',
        'user_name' => 'ahmed123',
    ]);

    $city = City::factory()->create([
        'name' => 'Cairo',
    ]);

    $plan = Plan::factory()->create([
        'name' => 'Professional',
        'monthly_price' => 250,
    ]);

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
        'city_id' => $city->id,
        'created_at' => '2026-08-01 15:30:00',
    ]);

    Subscription::factory()->create([
        'clinic_id' => $clinic->id,
        'plan_id' => $plan->id,
        'status' => SubscriptionStatus::ACTIVE,
    ]);

    $result = app(ClinicQueryService::class)->getAll();

    expect($result)
        ->toBeInstanceOf(LengthAwarePaginator::class);

    expect($result->count())
        ->toBe(1);

    $clinic = $result->items()[0];

    expect($clinic)
        ->toHaveKeys([
            'id',
            'name',
            'phone',
            'email',
            'status',
            'city',
            'plan',
            'joined_at',
            'owner',
            'address',
        ]);

    expect($clinic['status'])->toBe(SubscriptionStatus::ACTIVE);

    expect($clinic['joined_at'])
        ->toBe('2026-08-01');

    expect($clinic['owner']->id)
        ->toBe($owner->id);

    expect($clinic['owner']->name)
        ->toBe('Ahmed');

    expect($clinic['city']->id)
        ->toBe($city->id);

    expect($clinic['city']->name)
        ->toBe('Cairo');

    expect($clinic['plan']->id)
        ->toBe($plan->id);

    expect($clinic['plan']->name)
        ->toBe('Professional');
});
it('paginates five clinics per page', function () {

    Clinic::factory()->count(6)->create();

    $result = app(ClinicQueryService::class)->getAll();

    expect($result->count())->toBe(5);

    expect($result->total())->toBe(6);

    expect($result->lastPage())->toBe(2);
});
it('returns null status and plan when clinic has no subscription', function () {

    Clinic::factory()->create();

    $clinic = app(ClinicQueryService::class)
        ->getAll()
        ->items()[0];

    expect($clinic['status'])->toBeNull();

    expect($clinic['plan'])->toBeNull();
});
it('returns the latest subscription not the old one', function () {

    $clinic = Clinic::factory()->create();

    $plan1 = Plan::factory()->create([
        'name' => 'Basic',
    ]);

    $plan2 = Plan::factory()->create([
        'name' => 'Premium',
    ]);

    Subscription::factory()->create([
        'clinic_id' => $clinic->id,
        'plan_id' => $plan1->id,
        'status' => 'expired',
        'created_at' => now()->subMonth(),
    ]);

    Subscription::factory()->create([
        'clinic_id' => $clinic->id,
        'plan_id' => $plan2->id,
        'status' => SubscriptionStatus::ACTIVE,
        'created_at' => now(),
    ]);

    $result = app(ClinicQueryService::class)
        ->getAll()
        ->items()[0];

    expect($result['status'])->toBe(SubscriptionStatus::ACTIVE);

    expect($result['plan']->name)->toBe('Premium');
});
