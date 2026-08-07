<?php

use App\Enums\PlanStatus;
use App\Enums\SubscriptionStatus;
use App\Exceptions\ActiveSubscriptionAlreadyExistsException;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Support\Facades\DB;


beforeEach(function () {
    $this->service = app(SubscriptionService::class);
});


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function makeSubscriptionAddContext(array $overrides = []): array
{
    $clinic = Clinic::factory()->create();

    $plan = Plan::factory()->create([
        'monthly_price' => 500.00,
        'status' => PlanStatus::ACTIVE->value,
    ]);

    $data = array_merge([
        'clinic_id' => $clinic->id,
        'plan_id' => $plan->id,
    ], $overrides);

    return [
        'clinic' => $clinic,
        'plan' => $plan,
        'data' => $data,
    ];
}


/*
|--------------------------------------------------------------------------
| Return Type
|--------------------------------------------------------------------------
*/

it('returns a Subscription model', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    expect($result)
        ->toBeInstanceOf(Subscription::class);
});


/*
|--------------------------------------------------------------------------
| Database Creation
|--------------------------------------------------------------------------
*/

it('creates a subscription in the database', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    expect(
        DB::table('subscriptions')
            ->where('id', $result->id)
            ->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Correct Clinic
|--------------------------------------------------------------------------
*/

it('stores the correct clinic id', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    expect($result->clinic_id)
        ->toBe($context['clinic']->id);

    expect(
        DB::table('subscriptions')
            ->where('id', $result->id)
            ->value('clinic_id')
    )->toBe($context['clinic']->id);
});


/*
|--------------------------------------------------------------------------
| Correct Plan
|--------------------------------------------------------------------------
*/

it('stores the correct plan id', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    expect($result->plan_id)
        ->toBe($context['plan']->id);

    expect(
        DB::table('subscriptions')
            ->where('id', $result->id)
            ->value('plan_id')
    )->toBe($context['plan']->id);
});


/*
|--------------------------------------------------------------------------
| Price Comes From Plan
|--------------------------------------------------------------------------
*/

it('uses the monthly price from the selected plan', function () {
    $context = makeSubscriptionAddContext([
        'plan_id' => Plan::factory()->create([
            'monthly_price' => 875.50,
            'status' => PlanStatus::ACTIVE->value,
        ])->id,
    ]);

    $plan = Plan::findOrFail($context['data']['plan_id']);

    $result = $this->service->add($context['data']);

    expect((float) $result->price)
        ->toBe((float) $plan->monthly_price);
});


/*
|--------------------------------------------------------------------------
| Price Cannot Be Controlled By Input
|--------------------------------------------------------------------------
*/

it('does not use a price supplied in the input data', function () {
    $context = makeSubscriptionAddContext();

    $data = $context['data'];

    $data['price'] = 999999.99;

    $result = $this->service->add($data);

    expect((float) $result->price)
        ->toBe((float) $context['plan']->monthly_price);
});


/*
|--------------------------------------------------------------------------
| Start Date
|--------------------------------------------------------------------------
*/

it('sets start_at to the current date', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    expect($result->start_at)
        ->toBe(now()->toDateString());
});


/*
|--------------------------------------------------------------------------
| End Date
|--------------------------------------------------------------------------
*/

it('sets end_at one month after start_at', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    $expectedStart = now()->toDateString();

    $expectedEnd = now()
        ->addMonth()
        ->toDateString();

    expect($result->start_at)
        ->toBe($expectedStart);

    expect($result->end_at)
        ->toBe($expectedEnd);
});


/*
|--------------------------------------------------------------------------
| Date Difference
|--------------------------------------------------------------------------
*/

it('creates a subscription with a one month period', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    $start = \Carbon\Carbon::parse($result->start_at);
    $end = \Carbon\Carbon::parse($result->end_at);

    expect($end->isSameDay(
        $start->copy()->addMonth()
    ))->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Dates Are Stored As Date Only
|--------------------------------------------------------------------------
*/

it('stores start_at and end_at as dates without time', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    expect($result->start_at)
        ->toMatch('/^\d{4}-\d{2}-\d{2}$/');

    expect($result->end_at)
        ->toMatch('/^\d{4}-\d{2}-\d{2}$/');
});


/*
|--------------------------------------------------------------------------
| Only One Record Created
|--------------------------------------------------------------------------
*/

it('creates exactly one subscription', function () {
    $context = makeSubscriptionAddContext();

    $before = Subscription::count();

    $this->service->add($context['data']);

    $after = Subscription::count();

    expect($after)
        ->toBe($before + 1);
});


/*
|--------------------------------------------------------------------------
| Returned Model Is Persisted
|--------------------------------------------------------------------------
*/

it('returns a persisted subscription', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    expect($result->exists)
        ->toBeTrue();

    expect($result->wasRecentlyCreated)
        ->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Database Values Match Returned Model
|--------------------------------------------------------------------------
*/

it('returns values matching the persisted database record', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    $database = Subscription::findOrFail($result->id);

    expect($database->clinic_id)
        ->toBe($result->clinic_id);

    expect($database->plan_id)
        ->toBe($result->plan_id);

    expect((float) $database->price)
        ->toBe((float) $result->price);

    expect($database->start_at)
        ->toBe($result->start_at);

    expect($database->end_at)
        ->toBe($result->end_at);
});


/*
|--------------------------------------------------------------------------
| Active Plan Required
|--------------------------------------------------------------------------
*/

it('does not create a subscription for an inactive plan', function () {
    $clinic = Clinic::factory()->create();

    $plan = Plan::factory()->create([
        'monthly_price' => 500,
        'status' => 'inactive',
    ]);

    $data = [
        'clinic_id' => $clinic->id,
        'plan_id' => $plan->id,
    ];

    expect(fn () => $this->service->add($data))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

    expect(
        Subscription::where('clinic_id', $clinic->id)->exists()
    )->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Non Existing Plan
|--------------------------------------------------------------------------
*/

it('throws when the plan does not exist', function () {
    $clinic = Clinic::factory()->create();

    $data = [
        'clinic_id' => $clinic->id,
        'plan_id' => 999999,
    ];

    expect(fn () => $this->service->add($data))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

    expect(
        Subscription::where('clinic_id', $clinic->id)->exists()
    )->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Non Existing Clinic
|--------------------------------------------------------------------------
*/

it('throws when the clinic does not exist', function () {
    $plan = Plan::factory()->create([
        'monthly_price' => 500,
        'status' => PlanStatus::ACTIVE->value,
    ]);

    $data = [
        'clinic_id' => 999999,
        'plan_id' => $plan->id,
    ];

    expect(fn () => $this->service->add($data))
        ->toThrow(\Illuminate\Database\Eloquent\ModelNotFoundException::class);

    expect(Subscription::count())
        ->toBe(0);
});


/*
|--------------------------------------------------------------------------
| Active Subscription Validation
|--------------------------------------------------------------------------
*/

it('does not create another subscription when an active subscription exists', function () {
    $context = makeSubscriptionAddContext();

    Subscription::factory()->create([
        'clinic_id' => $context['clinic']->id,
        'plan_id' => $context['plan']->id,
        'status' => SubscriptionStatus::ACTIVE->value,
    ]);

    $before = Subscription::count();

    expect(fn () => $this->service->add($context['data']))
        ->toThrow(ActiveSubscriptionAlreadyExistsException::class);

    expect(Subscription::count())
        ->toBe($before);
});


/*
|--------------------------------------------------------------------------
| Different Clinics Can Subscribe
|--------------------------------------------------------------------------
*/

it('allows different clinics to create subscriptions independently', function () {
    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();

    $plan = Plan::factory()->create([
        'monthly_price' => 500,
        'status' => PlanStatus::ACTIVE->value,
    ]);

    $first = $this->service->add([
        'clinic_id' => $clinicOne->id,
        'plan_id' => $plan->id,
    ]);

    $second = $this->service->add([
        'clinic_id' => $clinicTwo->id,
        'plan_id' => $plan->id,
    ]);

    expect($first->id)
        ->not->toBe($second->id);

    expect($first->clinic_id)
        ->toBe($clinicOne->id);

    expect($second->clinic_id)
        ->toBe($clinicTwo->id);
});


/*
|--------------------------------------------------------------------------
| Different Plans
|--------------------------------------------------------------------------
*/

it('stores the selected plan when multiple active plans exist', function () {
    $clinic = Clinic::factory()->create();

    $planOne = Plan::factory()->create([
        'monthly_price' => 300,
        'status' => PlanStatus::ACTIVE->value,
    ]);

    $planTwo = Plan::factory()->create([
        'monthly_price' => 700,
        'status' => PlanStatus::ACTIVE->value,
    ]);

    $result = $this->service->add([
        'clinic_id' => $clinic->id,
        'plan_id' => $planTwo->id,
    ]);

    expect($result->plan_id)
        ->toBe($planTwo->id);

    expect((float) $result->price)
        ->toBe(700.0);
});


/*
|--------------------------------------------------------------------------
| Existing Subscriptions Remain Untouched
|--------------------------------------------------------------------------
*/

it('does not modify existing subscriptions of other clinics', function () {
    $first = makeSubscriptionAddContext();

    $otherClinic = Clinic::factory()->create();

    $otherPlan = Plan::factory()->create([
        'monthly_price' => 900,
        'status' => PlanStatus::ACTIVE->value,
    ]);

    $existing = Subscription::factory()->create([
        'clinic_id' => $otherClinic->id,
        'plan_id' => $otherPlan->id,
        'price' => 900,
        'start_at' => '2026-01-01',
        'end_at' => '2026-02-01',
    ]);

    $this->service->add($first['data']);

    $existing->refresh();

    expect($existing->clinic_id)
        ->toBe($otherClinic->id);

    expect($existing->plan_id)
        ->toBe($otherPlan->id);

    expect((float) $existing->price)
        ->toBe(900.0);

    expect($existing->start_at)
        ->toBe('2026-01-01');

    expect($existing->end_at)
        ->toBe('2026-02-01');
});


/*
|--------------------------------------------------------------------------
| Input Is Not Mutated
|--------------------------------------------------------------------------
*/

it('does not modify the input data', function () {
    $context = makeSubscriptionAddContext();

    $data = $context['data'];
    $original = $data;

    $this->service->add($data);

    expect($data)
        ->toBe($original);
});


/*
|--------------------------------------------------------------------------
| Extra Input Fields Are Ignored
|--------------------------------------------------------------------------
*/

it('does not allow unrelated input fields to affect the subscription', function () {
    $context = makeSubscriptionAddContext();

    $data = $context['data'];

    $data['price'] = 99999;
    $data['start_at'] = '2000-01-01';
    $data['end_at'] = '2000-02-01';
    $data['status'] = 'cancelled';

    $result = $this->service->add($data);

    expect($result->clinic_id)
        ->toBe($context['clinic']->id);

    expect($result->plan_id)
        ->toBe($context['plan']->id);

    expect((float) $result->price)
        ->toBe((float) $context['plan']->monthly_price);

    expect($result->start_at)
        ->toBe(now()->toDateString());
});


/*
|--------------------------------------------------------------------------
| Zero Price Plan
|--------------------------------------------------------------------------
*/

it('allows a zero priced active plan if the database permits it', function () {
    $context = makeSubscriptionAddContext();

    $context['plan']->update([
        'monthly_price' => 0,
    ]);

    $result = $this->service->add($context['data']);

    expect((float) $result->price)
        ->toBe(0.0);
});


/*
|--------------------------------------------------------------------------
| Decimal Price
|--------------------------------------------------------------------------
*/

it('preserves decimal plan prices', function () {
    $context = makeSubscriptionAddContext();

    $context['plan']->update([
        'monthly_price' => 1234.75,
    ]);

    $result = $this->service->add($context['data']);

    expect((float) $result->price)
        ->toBe(1234.75);
});


/*
|--------------------------------------------------------------------------
| Relationships
|--------------------------------------------------------------------------
*/

it('creates a subscription with a valid clinic relationship', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    $result->load('clinic');

    expect($result->clinic)
        ->not->toBeNull()
        ->and($result->clinic->id)
        ->toBe($context['clinic']->id);
});


it('creates a subscription with a valid plan relationship', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    $result->load('plan');

    expect($result->plan)
        ->not->toBeNull()
        ->and($result->plan->id)
        ->toBe($context['plan']->id);
});


/*
|--------------------------------------------------------------------------
| Transaction Rollback - Validation Failure
|--------------------------------------------------------------------------
*/

it('does not leave partial database changes when validation fails', function () {
    $context = makeSubscriptionAddContext();

    Subscription::factory()->create([
        'clinic_id' => $context['clinic']->id,
        'plan_id' => $context['plan']->id,
        'status' => SubscriptionStatus::ACTIVE,
    ]);

    $before = Subscription::count();

    expect(fn () => $this->service->add($context['data']))
        ->toThrow(ActiveSubscriptionAlreadyExistsException::class);

    expect(Subscription::count())
        ->toBe($before);
});

/*
|--------------------------------------------------------------------------
| Multiple Active Plans
|--------------------------------------------------------------------------
*/

it('does not accidentally select another active plan', function () {
    $context = makeSubscriptionAddContext();

    $anotherPlan = Plan::factory()->create([
        'monthly_price' => 999,
        'status' => PlanStatus::ACTIVE->value,
    ]);

    $result = $this->service->add([
        'clinic_id' => $context['clinic']->id,
        'plan_id' => $context['plan']->id,
    ]);

    expect($result->plan_id)
        ->toBe($context['plan']->id);

    expect((float) $result->price)
        ->toBe((float) $context['plan']->monthly_price);

    expect((float) $result->price)
        ->not->toBe((float) $anotherPlan->monthly_price);
});


/*
|--------------------------------------------------------------------------
| Count By Clinic
|--------------------------------------------------------------------------
*/

it('creates the subscription only for the requested clinic', function () {
    $context = makeSubscriptionAddContext();

    $otherClinic = Clinic::factory()->create();

    $result = $this->service->add($context['data']);

    expect(
        Subscription::where('clinic_id', $context['clinic']->id)->count()
    )->toBe(1);

    expect(
        Subscription::where('clinic_id', $otherClinic->id)->count()
    )->toBe(0);

    expect($result->clinic_id)
        ->toBe($context['clinic']->id);
});


/*
|--------------------------------------------------------------------------
| SQL Query Behavior
|--------------------------------------------------------------------------
*/

it('does not execute unnecessary queries after the subscription is created', function () {
    $context = makeSubscriptionAddContext();

    DB::enableQueryLog();

    $this->service->add($context['data']);

    $queries = DB::getQueryLog();

    DB::disableQueryLog();

    expect(count($queries))
        ->toBeGreaterThan(0);
});


/*
|--------------------------------------------------------------------------
| Plan Price Snapshot
|--------------------------------------------------------------------------
*/

it('stores the plan price as a snapshot on the subscription', function () {
    $context = makeSubscriptionAddContext();

    $originalPrice = (float) $context['plan']->monthly_price;

    $subscription = $this->service->add($context['data']);

    $context['plan']->update([
        'monthly_price' => $originalPrice + 500,
    ]);

    $subscription->refresh();

    expect((float) $subscription->price)
        ->toBe($originalPrice);
});


/*
|--------------------------------------------------------------------------
| Exact Database State
|--------------------------------------------------------------------------
*/

it('creates the exact expected database row', function () {
    $context = makeSubscriptionAddContext();

    $result = $this->service->add($context['data']);

    $row = DB::table('subscriptions')
        ->where('id', $result->id)
        ->first();

    expect($row->clinic_id)
        ->toBe($context['clinic']->id);

    expect($row->plan_id)
        ->toBe($context['plan']->id);

    expect((float) $row->price)
        ->toBe((float) $context['plan']->monthly_price);

    expect($row->start_at)
        ->toBe(now()->toDateString());

    expect($row->end_at)
        ->toBe(
            now()->addMonth()->toDateString()
        );
});


/*
|--------------------------------------------------------------------------
| Repeated Calls
|--------------------------------------------------------------------------
*/

it('creates separate subscriptions for separate clinics on repeated calls', function () {
    $plan = Plan::factory()->create([
        'monthly_price' => 500,
        'status' => PlanStatus::ACTIVE->value,
    ]);

    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();

    $first = $this->service->add([
        'clinic_id' => $clinicOne->id,
        'plan_id' => $plan->id,
    ]);

    $second = $this->service->add([
        'clinic_id' => $clinicTwo->id,
        'plan_id' => $plan->id,
    ]);

    expect($first->id)
        ->not->toBe($second->id);

    expect(Subscription::count())
        ->toBe(2);
});