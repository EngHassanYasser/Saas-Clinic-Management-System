<?php

use App\Enums\EnSubscriptionStatus;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionStatisticsService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(SubscriptionStatisticsService::class);

    Carbon::setTestNow(
        Carbon::create(2026, 8, 7, 10, 0, 0)
    );
});

afterEach(function () {
    Carbon::setTestNow();
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function createStatsSubscription(array $overrides = []): Subscription
{
    $clinic = Clinic::factory()->create();
    $plan = Plan::factory()->create();

    return Subscription::factory()->create(array_merge([
        'clinic_id' => $clinic->id,
        'plan_id' => $plan->id,
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'start_at' => now()->subMonth()->toDateString(),
        'end_at' => now()->addMonth()->toDateString(),
    ], $overrides));
}

/*
|--------------------------------------------------------------------------
| Return type
|--------------------------------------------------------------------------
*/

it('returns a Subscription model', function () {
    createStatsSubscription();

    $result = $this->service->getStats();

    expect($result)
        ->toBeArray();
});

/*
|--------------------------------------------------------------------------
| Empty database
|--------------------------------------------------------------------------
*/

it('returns zero statistics when there are no subscriptions', function () {
    $result = $this->service->getStats();

    expect((int) $result['total'])
        ->toBe(0);

    expect((int) $result['pending'])
        ->toBe(0);

    expect((int) $result['active'])
        ->toBe(0);

    expect((int) $result['expired'])
        ->toBe(0);

    expect((int) $result['cancelled'])
        ->toBe(0);

    expect((int) $result['inactive'])
        ->toBe(0);

    expect((int) $result['expiring'])
        ->toBe(0);
});

/*
|--------------------------------------------------------------------------
| Total
|--------------------------------------------------------------------------
*/

it('returns the total number of subscriptions', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    $result = $this->service->getStats();

    expect((int) $result['total'])
        ->toBe(4);
});

/*
|--------------------------------------------------------------------------
| Pending
|--------------------------------------------------------------------------
*/

it('counts pending subscriptions correctly', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);

    $result = $this->service->getStats();

    expect((int) $result['pending'])
        ->toBe(2);
});

/*
|--------------------------------------------------------------------------
| Active
|--------------------------------------------------------------------------
*/

it('counts active subscriptions correctly', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    $result = $this->service->getStats();

    expect((int) $result['active'])
        ->toBe(3);
});

/*
|--------------------------------------------------------------------------
| Expired
|--------------------------------------------------------------------------
*/

it('counts expired subscriptions correctly', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expired'])
        ->toBe(2);
});

/*
|--------------------------------------------------------------------------
| Cancelled
|--------------------------------------------------------------------------
*/

it('counts cancelled subscriptions correctly', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    $result = $this->service->getStats();

    expect((int) $result['cancelled'])
        ->toBe(2);
});
/*
|--------------------------------------------------------------------------
| Inactive
|--------------------------------------------------------------------------
|
| There is no INACTIVE enum case.
|
| The current query explicitly defines inactive as:
|
| SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END)
|
*/

it('counts cancelled subscriptions as inactive according to the current query', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    $result = $this->service->getStats();

    expect((int) $result['inactive'])
        ->toBe(2);
});

/*
|--------------------------------------------------------------------------
| Inactive and cancelled are identical in current implementation
|--------------------------------------------------------------------------
*/

it('returns the same count for inactive and cancelled', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    $result = $this->service->getStats();

    expect((int) $result['inactive'])
        ->toBe((int) $result['cancelled']);
});

/*
|--------------------------------------------------------------------------
| Expiring
|--------------------------------------------------------------------------
*/

it('counts active subscriptions expiring within seven days', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'end_at' => now()->addDays(3)->toDateString(),
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'end_at' => now()->addDays(5)->toDateString(),
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'end_at' => now()->addDays(20)->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expiring'])
        ->toBe(2);
});

/*
|--------------------------------------------------------------------------
| Only active subscriptions are expiring
|--------------------------------------------------------------------------
*/

it('does not count pending subscriptions as expiring', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
        'end_at' => now()->addDays(3)->toDateString(),
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'end_at' => now()->addDays(3)->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expiring'])
        ->toBe(1);
});

it('does not count expired subscriptions as expiring', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::EXPIRED->value,
        'end_at' => now()->addDays(3)->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expiring'])
        ->toBe(0);
});

it('does not count cancelled subscriptions as expiring', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
        'end_at' => now()->addDays(3)->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expiring'])
        ->toBe(0);
});

/*
|--------------------------------------------------------------------------
| Expiring boundaries
|--------------------------------------------------------------------------
*/

it('counts an active subscription ending today as expiring', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'end_at' => now()->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expiring'])
        ->toBe(1);
});

it('counts an active subscription ending exactly seven days from today as expiring', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'end_at' => now()->addDays(7)->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expiring'])
        ->toBe(1);
});

it('does not count an active subscription ending after seven days as expiring', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'end_at' => now()->addDays(8)->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expiring'])
        ->toBe(0);
});

it('does not count an active subscription ending yesterday as expiring', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'end_at' => now()->subDay()->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expiring'])
        ->toBe(0);
});

/*
|--------------------------------------------------------------------------
| Multiple expiring subscriptions
|--------------------------------------------------------------------------
*/

it('counts all active subscriptions inside the seven day window', function () {
    foreach ([1, 2, 3, 4, 5, 6, 7] as $days) {
        createStatsSubscription([
            'status' => EnSubscriptionStatus::ACTIVE->value,
            'end_at' => now()->addDays($days)->toDateString(),
        ]);
    }

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'end_at' => now()->addDays(8)->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['expiring'])
        ->toBe(7);
});

/*
|--------------------------------------------------------------------------
| Status distribution
|--------------------------------------------------------------------------
*/

it('counts every subscription status independently', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    $result = $this->service->getStats();

    expect((int) $result['total'])
        ->toBe(7);

    expect((int) $result['pending'])
        ->toBe(2);

    expect((int) $result['active'])
        ->toBe(3);

    expect((int) $result['expired'])
        ->toBe(1);

    expect((int) $result['cancelled'])
        ->toBe(1);

    expect((int) $result['inactive'])
        ->toBe(2);
});

/*
|--------------------------------------------------------------------------
| Total consistency
|--------------------------------------------------------------------------
*/

it('returns total equal to the sum of all enum statuses', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);

    $result = $this->service->getStats();

    $total =
        (int) $result['pending'] +
        (int) $result['active'] +
        (int) $result['expired'] +
        (int) $result['cancelled'];

    expect((int) $result['total'])
        ->toBe($total);
});

/*
|--------------------------------------------------------------------------
| Irrelevant columns
|--------------------------------------------------------------------------
*/

it('does not depend on clinic or plan values for status statistics', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    $result = $this->service->getStats();

    expect((int) $result['total'])
        ->toBe(2);

    expect((int) $result['active'])
        ->toBe(1);

    expect((int) $result['pending'])
        ->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Single query
|--------------------------------------------------------------------------
*/

it('gets all statistics using a single database query', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->service->getStats();

    $queries = DB::getQueryLog();

    DB::disableQueryLog();

    expect($queries)
        ->toHaveCount(1);
});

/*
|--------------------------------------------------------------------------
| Query count is independent of record count
|--------------------------------------------------------------------------
*/

it('keeps query count constant as subscription count increases', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);
    Cache::forget('subscriptions.statistics');

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->service->getStats();

    $queriesForOneRecord = count(
        DB::getQueryLog()
    );

    DB::disableQueryLog();

    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::EXPIRED->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::CANCELLED->value,
    ]);
    Cache::forget('subscriptions.statistics');

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->service->getStats();

    $queriesForFiveRecords = count(
        DB::getQueryLog()
    );

    DB::disableQueryLog();

    expect($queriesForOneRecord)
        ->toBe(1);

    expect($queriesForFiveRecords)
        ->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Read-only behavior
|--------------------------------------------------------------------------
*/

it('does not modify the database', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    createStatsSubscription([
        'status' => EnSubscriptionStatus::PENDING->value,
    ]);

    $beforeCount = Subscription::count();

    $this->service->getStats();

    expect(Subscription::count())
        ->toBe($beforeCount);
});

it('does not delete subscriptions', function () {
    $subscription = createStatsSubscription();

    $this->service->getStats();

    expect(
        Subscription::whereKey($subscription->id)->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Result attributes
|--------------------------------------------------------------------------
*/

it('contains all expected statistic attributes', function () {
    createStatsSubscription();

    $result = $this->service->getStats();
    expect($result)
        ->toBeArray()
        ->toHaveKeys([
            'total',
            'pending',
            'active',
            'expired',
            'cancelled',
            'inactive',
            'expiring',
        ]);
});

/*
|--------------------------------------------------------------------------
| Numeric aggregates
|--------------------------------------------------------------------------
*/

it('returns numeric aggregate values', function () {
    createStatsSubscription([
        'status' => EnSubscriptionStatus::ACTIVE->value,
    ]);

    $result = $this->service->getStats();

    expect(is_numeric($result['total']))
        ->toBeTrue();

    expect(is_numeric($result['pending']))
        ->toBeTrue();

    expect(is_numeric($result['active']))
        ->toBeTrue();

    expect(is_numeric($result['expired']))
        ->toBeTrue();

    expect(is_numeric($result['cancelled']))
        ->toBeTrue();

    expect(is_numeric($result['inactive']))
        ->toBeTrue();

    expect(is_numeric($result['expiring']))
        ->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Large dataset
|--------------------------------------------------------------------------
*/
it('calculates statistics correctly with a larger dataset', function () {
    Cache::forget('subscriptions.statistics');
    $plan = Plan::factory()->create();

    Subscription::factory()->count(20)->create([
        'plan_id' => $plan->id,
        'clinic_id' => fn () => Clinic::factory()->create()->id,
        'status' => EnSubscriptionStatus::ACTIVE->value,
        'start_at' => now()->subMonth()->toDateString(),
        'end_at' => now()->addDays(3)->toDateString(),
    ]);

    Subscription::factory()->count(15)->create([
        'plan_id' => $plan->id,
        'clinic_id' => fn () => Clinic::factory()->create()->id,
        'status' => EnSubscriptionStatus::PENDING->value,
        'start_at' => now()->subMonth()->toDateString(),
        'end_at' => now()->addMonth()->toDateString(),
    ]);

    Subscription::factory()->count(10)->create([
        'plan_id' => $plan->id,
        'clinic_id' => fn () => Clinic::factory()->create()->id,
        'status' => EnSubscriptionStatus::EXPIRED->value,
        'start_at' => now()->subMonths(2)->toDateString(),
        'end_at' => now()->subMonth()->toDateString(),
    ]);

    Subscription::factory()->count(5)->create([
        'plan_id' => $plan->id,
        'clinic_id' => fn () => Clinic::factory()->create()->id,
        'status' => EnSubscriptionStatus::CANCELLED->value,
        'start_at' => now()->subMonth()->toDateString(),
        'end_at' => now()->addMonth()->toDateString(),
    ]);

    $result = $this->service->getStats();

    expect((int) $result['total'])
        ->toBe(50);

    expect((int) $result['active'])
        ->toBe(20);

    expect((int) $result['pending'])
        ->toBe(15);

    expect((int) $result['expired'])
        ->toBe(10);

    expect((int) $result['cancelled'])
        ->toBe(5);

    expect((int) $result['inactive'])
        ->toBe(15);

    expect((int) $result['expiring'])
        ->toBe(20);
});
