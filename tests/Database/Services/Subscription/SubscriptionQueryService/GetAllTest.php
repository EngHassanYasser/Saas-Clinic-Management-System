<?php

use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionQueryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(SubscriptionQueryService::class);
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function createSubscriptionContext(array $subscriptionData = []): array
{
    $clinic = Clinic::factory()->create();

    $plan = Plan::factory()->create();

    $subscription = Subscription::factory()->create(array_merge([
        'clinic_id' => $clinic->id,
        'plan_id' => $plan->id,
    ], $subscriptionData));

    return [
        'clinic' => $clinic,
        'plan' => $plan,
        'subscription' => $subscription,
    ];
}

/*
|--------------------------------------------------------------------------
| Return type
|--------------------------------------------------------------------------
*/

it('returns a collection', function () {
    $result = $this->service->getAll();

    expect($result)
        ->toBeInstanceOf(Collection::class);
});

/*
|--------------------------------------------------------------------------
| Empty database
|--------------------------------------------------------------------------
*/

it('returns an empty collection when there are no subscriptions', function () {
    $result = $this->service->getAll();

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

/*
|--------------------------------------------------------------------------
| Returns all subscriptions
|--------------------------------------------------------------------------
*/

it('returns all subscriptions', function () {
    $first = createSubscriptionContext();
    $second = createSubscriptionContext();
    $third = createSubscriptionContext();

    $result = $this->service->getAll();

    expect($result)
        ->toHaveCount(3);

    expect($result->pluck('id')->sort()->values()->all())
        ->toBe(
            collect([
                $first['subscription']->id,
                $second['subscription']->id,
                $third['subscription']->id,
            ])->sort()->values()->all()
        );
});

/*
|--------------------------------------------------------------------------
| Correct model
|--------------------------------------------------------------------------
*/

it('returns Subscription models', function () {
    createSubscriptionContext();

    $result = $this->service->getAll();

    expect($result->every(
        fn ($subscription) => $subscription instanceof Subscription
    ))->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Database persistence
|--------------------------------------------------------------------------
*/

it('returns subscriptions that actually exist in the database', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    expect(
        $result->contains(
            fn (Subscription $subscription) => $subscription->id === $context['subscription']->id
        )
    )->toBeTrue();

    expect(
        DB::table('subscriptions')
            ->where('id', $context['subscription']->id)
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Selected columns
|--------------------------------------------------------------------------
*/

it('selects all required subscription columns', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect($subscription)->not->toBeNull();

    expect($subscription->getAttributes())
        ->toHaveKeys([
            'id',
            'start_at',
            'end_at',
            'status',
            'price',
            'auto_renew',
            'clinic_id',
            'plan_id',
        ]);
});

/*
|--------------------------------------------------------------------------
| Hidden clinic_id
|--------------------------------------------------------------------------
*/

it('hides clinic_id from the returned subscription attributes', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect($subscription->toArray())
        ->not->toHaveKey('clinic_id');
});

/*
|--------------------------------------------------------------------------
| Hidden plan_id
|--------------------------------------------------------------------------
*/

it('hides plan_id from the returned subscription attributes', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect($subscription->toArray())
        ->not->toHaveKey('plan_id');
});

/*
|--------------------------------------------------------------------------
| Hidden columns are still loaded internally
|--------------------------------------------------------------------------
*/

it('still has clinic_id and plan_id loaded internally before serialization', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect($subscription->getRawOriginal('clinic_id'))
        ->toBe($context['clinic']->id);

    expect($subscription->getRawOriginal('plan_id'))
        ->toBe($context['plan']->id);
});

/*
|--------------------------------------------------------------------------
| Other selected columns remain visible
|--------------------------------------------------------------------------
*/

it('keeps all other selected columns visible', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    $array = $subscription->toArray();

    expect($array)
        ->toHaveKeys([
            'id',
            'start_at',
            'end_at',
            'status',
            'price',
            'auto_renew',
        ])
        ->not->toHaveKeys([
            'clinic_id',
            'plan_id',
        ]);
});

/*
|--------------------------------------------------------------------------
| Clinic eager loading
|--------------------------------------------------------------------------
*/

it('eager loads the clinic relationship', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect($subscription->relationLoaded('clinic'))
        ->toBeTrue();

    expect($subscription->clinic)
        ->toBeInstanceOf(Clinic::class)
        ->not->toBeNull();

    expect($subscription->clinic->id)
        ->toBe($context['clinic']->id);
});

/*
|--------------------------------------------------------------------------
| Plan eager loading
|--------------------------------------------------------------------------
*/

it('eager loads the plan relationship', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect($subscription->relationLoaded('plan'))
        ->toBeTrue();

    expect($subscription->plan)
        ->toBeInstanceOf(Plan::class)
        ->not->toBeNull();

    expect($subscription->plan->id)
        ->toBe($context['plan']->id);
});

/*
|--------------------------------------------------------------------------
| Plan selected columns
|--------------------------------------------------------------------------
*/

it('loads only the required plan columns', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $plan = $result
        ->firstWhere('id', $context['subscription']->id)
        ->plan;

    expect($plan->getAttributes())
        ->toHaveKeys([
            'id',
            'name',
            'monthly_price',
        ]);

    expect($plan->getAttributes())
        ->not->toHaveKey('features');
});

/*
|--------------------------------------------------------------------------
| Clinic selected columns
|--------------------------------------------------------------------------
*/

it('loads only the required clinic columns', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $clinic = $result
        ->firstWhere('id', $context['subscription']->id)
        ->clinic;

    expect($clinic->getAttributes())
        ->toHaveKeys([
            'id',
            'name',
        ]);

    /*
     * These are intentionally not selected by the service.
     * The exact assertion assumes these columns exist on clinics.
     */
    expect($clinic->getAttributes())
        ->not->toHaveKey('email');
});

/*
|--------------------------------------------------------------------------
| Relationship correctness
|--------------------------------------------------------------------------
*/

it('returns the correct clinic and plan for every subscription', function () {
    $first = createSubscriptionContext();
    $second = createSubscriptionContext();
    $third = createSubscriptionContext();

    $result = $this->service->getAll();

    $firstResult = $result->firstWhere(
        'id',
        $first['subscription']->id
    );

    $secondResult = $result->firstWhere(
        'id',
        $second['subscription']->id
    );

    $thirdResult = $result->firstWhere(
        'id',
        $third['subscription']->id
    );

    expect($firstResult->clinic->id)
        ->toBe($first['clinic']->id);

    expect($firstResult->plan->id)
        ->toBe($first['plan']->id);

    expect($secondResult->clinic->id)
        ->toBe($second['clinic']->id);

    expect($secondResult->plan->id)
        ->toBe($second['plan']->id);

    expect($thirdResult->clinic->id)
        ->toBe($third['clinic']->id);

    expect($thirdResult->plan->id)
        ->toBe($third['plan']->id);
});

/*
|--------------------------------------------------------------------------
| Relationship models
|--------------------------------------------------------------------------
*/

it('returns actual clinic and plan models instead of only foreign keys', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect($subscription->clinic)
        ->toBeInstanceOf(Clinic::class);

    expect($subscription->plan)
        ->toBeInstanceOf(Plan::class);
});

/*
|--------------------------------------------------------------------------
| No relationship mixing
|--------------------------------------------------------------------------
*/

it('does not mix clinic or plan relationships between subscriptions', function () {
    $first = createSubscriptionContext();
    $second = createSubscriptionContext();

    $result = $this->service->getAll();

    $firstResult = $result->firstWhere(
        'id',
        $first['subscription']->id
    );

    $secondResult = $result->firstWhere(
        'id',
        $second['subscription']->id
    );

    expect($firstResult->clinic->id)
        ->toBe($first['clinic']->id)
        ->not->toBe($second['clinic']->id);

    expect($firstResult->plan->id)
        ->toBe($first['plan']->id)
        ->not->toBe($second['plan']->id);

    expect($secondResult->clinic->id)
        ->toBe($second['clinic']->id)
        ->not->toBe($first['clinic']->id);

    expect($secondResult->plan->id)
        ->toBe($second['plan']->id)
        ->not->toBe($first['plan']->id);
});

/*
|--------------------------------------------------------------------------
| Multiple subscriptions for same clinic
|--------------------------------------------------------------------------
*/

it('correctly handles multiple subscriptions belonging to the same clinic', function () {
    $clinic = Clinic::factory()->create();

    $firstPlan = Plan::factory()->create();
    $secondPlan = Plan::factory()->create();

    $first = Subscription::factory()->create([
        'clinic_id' => $clinic->id,
        'plan_id' => $firstPlan->id,
    ]);

    $second = Subscription::factory()->create([
        'clinic_id' => $clinic->id,
        'plan_id' => $secondPlan->id,
    ]);

    $result = $this->service->getAll();

    expect($result)->toHaveCount(2);

    $firstResult = $result->firstWhere('id', $first->id);
    $secondResult = $result->firstWhere('id', $second->id);

    expect($firstResult->clinic->id)
        ->toBe($clinic->id);

    expect($secondResult->clinic->id)
        ->toBe($clinic->id);

    expect($firstResult->plan->id)
        ->toBe($firstPlan->id);

    expect($secondResult->plan->id)
        ->toBe($secondPlan->id);
});

/*
|--------------------------------------------------------------------------
| Multiple subscriptions for same plan
|--------------------------------------------------------------------------
*/

it('correctly handles multiple subscriptions using the same plan', function () {
    $plan = Plan::factory()->create();

    $firstClinic = Clinic::factory()->create();
    $secondClinic = Clinic::factory()->create();

    $first = Subscription::factory()->create([
        'clinic_id' => $firstClinic->id,
        'plan_id' => $plan->id,
    ]);

    $second = Subscription::factory()->create([
        'clinic_id' => $secondClinic->id,
        'plan_id' => $plan->id,
    ]);

    $result = $this->service->getAll();

    $firstResult = $result->firstWhere('id', $first->id);
    $secondResult = $result->firstWhere('id', $second->id);

    expect($firstResult->plan->id)
        ->toBe($plan->id);

    expect($secondResult->plan->id)
        ->toBe($plan->id);

    expect($firstResult->clinic->id)
        ->toBe($firstClinic->id);

    expect($secondResult->clinic->id)
        ->toBe($secondClinic->id);
});

/*
|--------------------------------------------------------------------------
| No N+1 queries
|--------------------------------------------------------------------------
*/

it('does not execute N+1 queries when accessing clinic and plan relationships', function () {
    createSubscriptionContext();
    createSubscriptionContext();
    createSubscriptionContext();
    createSubscriptionContext();
    createSubscriptionContext();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $result = $this->service->getAll();

    foreach ($result as $subscription) {
        $subscription->clinic;
        $subscription->plan;
    }

    $queries = DB::getQueryLog();

    DB::disableQueryLog();

    /*
     * Expected:
     *
     * 1 query = subscriptions
     * 1 query = clinics
     * 1 query = plans
     *
     * Total = 3.
     */
    expect(count($queries))
        ->toBe(3);
});

/*
|--------------------------------------------------------------------------
| Query count remains constant
|--------------------------------------------------------------------------
*/

it('keeps query count constant regardless of number of subscriptions', function () {
    createSubscriptionContext();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->service->getAll();

    $queriesForOneRecord = count(DB::getQueryLog());

    DB::disableQueryLog();

    createSubscriptionContext();
    createSubscriptionContext();
    createSubscriptionContext();
    createSubscriptionContext();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $result = $this->service->getAll();

    foreach ($result as $subscription) {
        $subscription->clinic;
        $subscription->plan;
    }

    $queriesForFiveRecords = count(DB::getQueryLog());

    DB::disableQueryLog();

    expect($queriesForOneRecord)
        ->toBe(3);

    expect($queriesForFiveRecords)
        ->toBe(3);
});

/*
|--------------------------------------------------------------------------
| No duplicate subscriptions
|--------------------------------------------------------------------------
*/

it('returns every subscription exactly once', function () {
    $first = createSubscriptionContext();
    $second = createSubscriptionContext();
    $third = createSubscriptionContext();

    $result = $this->service->getAll();

    $ids = $result->pluck('id');

    expect($ids)
        ->toHaveCount(3)
        ->and($ids->unique()->count())
        ->toBe(3);

    expect($ids)
        ->toContain(
            $first['subscription']->id,
            $second['subscription']->id,
            $third['subscription']->id
        );
});

/*
|--------------------------------------------------------------------------
| Does not modify database
|--------------------------------------------------------------------------
*/

it('does not modify the database', function () {
    $first = createSubscriptionContext();
    $second = createSubscriptionContext();

    $beforeCount = Subscription::count();

    $this->service->getAll();

    expect(Subscription::count())
        ->toBe($beforeCount);

    expect(
        Subscription::whereKey($first['subscription']->id)->exists()
    )->toBeTrue();

    expect(
        Subscription::whereKey($second['subscription']->id)->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Calling method multiple times
|--------------------------------------------------------------------------
*/

it('returns consistent results when called multiple times', function () {
    createSubscriptionContext();
    createSubscriptionContext();

    $firstResult = $this->service->getAll();
    $secondResult = $this->service->getAll();

    expect($firstResult->pluck('id')->sort()->values()->all())
        ->toBe(
            $secondResult->pluck('id')->sort()->values()->all()
        );
});

/*
|--------------------------------------------------------------------------
| Subscription values
|--------------------------------------------------------------------------
*/

it('returns the correct subscription values', function () {
    $context = createSubscriptionContext([
        'price' => 499.99,
        'auto_renew' => true,
    ]);

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect((float) $subscription->price)
        ->toBe(499.99);

    expect((bool) $subscription->auto_renew)
        ->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Date values
|--------------------------------------------------------------------------
*/

it('returns the correct start and end dates', function () {
    $context = createSubscriptionContext([
        'start_at' => '2026-01-01',
        'end_at' => '2026-02-01',
    ]);

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect($subscription->start_at)
        ->toBe('2026-01-01');

    expect($subscription->end_at)
        ->toBe('2026-02-01');
});

/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

it('returns the correct subscription status', function () {
    $context = createSubscriptionContext();

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect($subscription->status)
        ->toBe($context['subscription']->status);
});

/*
|--------------------------------------------------------------------------
| Boolean casting
|--------------------------------------------------------------------------
*/

it('returns auto_renew with the correct boolean value', function () {
    $context = createSubscriptionContext([
        'auto_renew' => false,
    ]);

    $result = $this->service->getAll();

    $subscription = $result->firstWhere(
        'id',
        $context['subscription']->id
    );

    expect((bool) $subscription->auto_renew)
        ->toBeFalse();
});

/*
|--------------------------------------------------------------------------
| Large number of records
|--------------------------------------------------------------------------
*/

it('handles many subscriptions correctly', function () {
    Subscription::factory()
        ->count(50)
        ->create();

    $result = $this->service->getAll();

    expect($result)
        ->toHaveCount(50);

    expect($result->pluck('id')->unique()->count())
        ->toBe(50);
});

/*
|--------------------------------------------------------------------------
| Serialization
|--------------------------------------------------------------------------
*/

it('does not expose clinic_id and plan_id when serialized', function () {
    createSubscriptionContext();

    $subscription = $this->service->getAll()->first();

    $array = $subscription->toArray();

    expect($array)
        ->not->toHaveKeys([
            'clinic_id',
            'plan_id',
        ]);

    expect($array['clinic'])
        ->toHaveKeys([
            'id',
            'name',
        ]);

    expect($array['plan'])
        ->toHaveKeys([
            'id',
            'name',
            'monthly_price',
        ]);
});

/*
|--------------------------------------------------------------------------
| No lazy loading after getAll()
|--------------------------------------------------------------------------
*/

it('can access both relationships without triggering additional queries', function () {
    createSubscriptionContext();
    createSubscriptionContext();
    createSubscriptionContext();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $result = $this->service->getAll();

    DB::flushQueryLog();

    foreach ($result as $subscription) {
        $clinic = $subscription->clinic;
        $plan = $subscription->plan;

        expect($clinic)
            ->toBeInstanceOf(Clinic::class);

        expect($plan)
            ->toBeInstanceOf(Plan::class);
    }

    expect(DB::getQueryLog())
        ->toBeEmpty();

    DB::disableQueryLog();
});

/*
|--------------------------------------------------------------------------
| Relationship data correctness
|--------------------------------------------------------------------------
*/

it('returns the correct clinic name and plan data', function () {
    $clinic = Clinic::factory()->create([
        'name' => 'Main Clinic',
    ]);

    $plan = Plan::factory()->create([
        'name' => 'Premium Plan',
        'monthly_price' => 999.99,
    ]);

    $subscription = Subscription::factory()->create([
        'clinic_id' => $clinic->id,
        'plan_id' => $plan->id,
    ]);

    $result = $this->service->getAll();

    $item = $result->firstWhere(
        'id',
        $subscription->id
    );

    expect($item->clinic->id)
        ->toBe($clinic->id);

    expect($item->clinic->name)
        ->toBe('Main Clinic');

    expect($item->plan->id)
        ->toBe($plan->id);

    expect($item->plan->name)
        ->toBe('Premium Plan');

    expect((float) $item->plan->monthly_price)
        ->toBe(999.99);
});
