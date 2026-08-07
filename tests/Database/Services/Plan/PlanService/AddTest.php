<?php

use App\Enums\PlanStatus;
use App\Models\Plan;
use App\Services\Plan\PlanService;
use Illuminate\Database\QueryException;

beforeEach(function () {
    $this->service = app(PlanService::class);
});

it('creates a plan successfully', function () {

    $data = [
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
    ];

    $plan = $this->service->add($data);

    expect($plan)
        ->toBeInstanceOf(Plan::class)
        ->and($plan->exists)
        ->toBeTrue()
        ->and($plan->id)
        ->toBeGreaterThan(0);

    expect(
        Plan::whereKey($plan->id)->exists()
    )->toBeTrue();
});

it('stores all provided plan data correctly', function () {

    $data = [
        'name' => 'Professional',
        'monthly_price' => 250.50,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
    ];

    $plan = $this->service->add($data);

    expect($plan->name)
        ->toBe('Professional')
        ->and((float) $plan->monthly_price)
        ->toBe(250.50)
        ->and((int) $plan->max_doctors)
        ->toBe(20)
        ->and((int) $plan->monthly_appointments_limit)
        ->toBe(1000);

    $this->assertDatabaseHas('plans', [
        'id' => $plan->id,
        'name' => 'Professional',
        'monthly_price' => 250.50,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
    ]);
});

it('persists the plan data in the database', function () {

    $data = [
        'name' => 'Enterprise',
        'monthly_price' => 500,
        'max_doctors' => 50,
        'monthly_appointments_limit' => 5000,
    ];

    $plan = $this->service->add($data);

    $this->assertDatabaseHas('plans', [
        'id' => $plan->id,
        'name' => $data['name'],
        'monthly_price' => $data['monthly_price'],
        'max_doctors' => $data['max_doctors'],
        'monthly_appointments_limit' => $data['monthly_appointments_limit'],
    ]);
});

it('creates only one plan', function () {

    $before = Plan::count();

    $this->service->add([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
    ]);

    expect(Plan::count())
        ->toBe($before + 1);
});

it('generates a unique id for the new plan', function () {

    $plan = $this->service->add([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
    ]);

    expect($plan->id)
        ->not->toBeNull()
        ->toBeInt()
        ->toBeGreaterThan(0);
});

it('does not modify existing plans when creating a new plan', function () {

    $existingPlan = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $this->service->add([
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
    ]);

    $existingPlan->refresh();

    expect($existingPlan->name)
        ->toBe('Basic')
        ->and((float) $existingPlan->monthly_price)
        ->toBe(100.0)
        ->and((int) $existingPlan->max_doctors)
        ->toBe(5)
        ->and((int) $existingPlan->monthly_appointments_limit)
        ->toBe(500)
        ->and($existingPlan->status)
        ->toBe(PlanStatus::ACTIVE);
});

it('does not overwrite the name with another existing plan name', function () {

    Plan::factory()->create([
        'name' => 'Basic',
    ]);

    expect(fn () => $this->service->add([
        'name' => 'Basic',
        'monthly_price' => 200,
        'max_doctors' => 10,
        'monthly_appointments_limit' => 1000,
    ]))->toThrow(QueryException::class);
});

it('uses the database default status when status is not provided', function () {

    $plan = $this->service->add([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
    ]);

    $plan->refresh();

    expect($plan->status)
        ->not->toBeNull();
});

it('does not allow the add method to decide the status', function () {

    $plan = $this->service->add([
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
    ]);

    $plan->refresh();

    /*
     * The service does not provide status explicitly.
     * Therefore status must come from the model/database default.
     */
    expect($plan->getAttributes())
        ->toHaveKey('status');
});

it('stores decimal monthly price correctly', function () {

    $plan = $this->service->add([
        'name' => 'Basic',
        'monthly_price' => 99.99,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
    ]);

    $plan->refresh();

    expect((float) $plan->monthly_price)
        ->toBe(99.99);

    $this->assertDatabaseHas('plans', [
        'id' => $plan->id,
        'monthly_price' => 99.99,
    ]);
});

it('stores zero as a valid monthly price when allowed by the database', function () {

    $plan = $this->service->add([
        'name' => 'Free',
        'monthly_price' => 0,
        'max_doctors' => 1,
        'monthly_appointments_limit' => 50,
    ]);

    $plan->refresh();

    expect((float) $plan->monthly_price)
        ->toBe(0.0);

    $this->assertDatabaseHas('plans', [
        'id' => $plan->id,
        'monthly_price' => 0,
    ]);
});

it('stores the maximum configured doctor limit correctly', function () {

    $plan = $this->service->add([
        'name' => 'Enterprise',
        'monthly_price' => 1000,
        'max_doctors' => 999,
        'monthly_appointments_limit' => 5000,
    ]);

    $plan->refresh();

    expect((int) $plan->max_doctors)
        ->toBe(999)
        ->and((int) $plan->monthly_appointments_limit)
        ->toBe(5000);
});

it('returns the newly created plan with its persisted values', function () {

    $data = [
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
    ];

    $plan = $this->service->add($data);

    expect($plan->exists)
        ->toBeTrue()
        ->and($plan->wasRecentlyCreated)
        ->toBeTrue()
        ->and($plan->name)
        ->toBe($data['name'])
        ->and((float) $plan->monthly_price)
        ->toBe(250.0)
        ->and((int) $plan->max_doctors)
        ->toBe(20)
        ->and((int) $plan->monthly_appointments_limit)
        ->toBe(1000);
});

it('creates independent plans with different data', function () {

    $basic = $this->service->add([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
    ]);

    $professional = $this->service->add([
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
    ]);

    $enterprise = $this->service->add([
        'name' => 'Enterprise',
        'monthly_price' => 500,
        'max_doctors' => 50,
        'monthly_appointments_limit' => 5000,
    ]);

    expect(Plan::count())
        ->toBe(3);

    expect($basic->id)
        ->not->toBe($professional->id)
        ->and($professional->id)
        ->not->toBe($enterprise->id)
        ->and($basic->id)
        ->not->toBe($enterprise->id);
});

it('preserves the exact plan values after reloading from database', function () {

    $data = [
        'name' => 'Professional',
        'monthly_price' => 299.99,
        'max_doctors' => 25,
        'monthly_appointments_limit' => 1500,
    ];

    $plan = $this->service->add($data);

    $freshPlan = Plan::findOrFail($plan->id);

    expect($freshPlan->name)
        ->toBe('Professional')
        ->and((float) $freshPlan->monthly_price)
        ->toBe(299.99)
        ->and((int) $freshPlan->max_doctors)
        ->toBe(25)
        ->and((int) $freshPlan->monthly_appointments_limit)
        ->toBe(1500);
});

it('does not create an extra plan when creation fails because of duplicate name', function () {

    Plan::factory()->create([
        'name' => 'Basic',
    ]);

    $countBefore = Plan::count();

    expect(fn () => $this->service->add([
        'name' => 'Basic',
        'monthly_price' => 200,
        'max_doctors' => 10,
        'monthly_appointments_limit' => 1000,
    ]))->toThrow(QueryException::class);

    expect(Plan::count())
        ->toBe($countBefore);
});
