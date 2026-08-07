<?php

use App\Enums\PlanStatus;
use App\Models\Plan;
use App\Services\Plan\PlanQueryService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->service = app(PlanQueryService::class);
});

it('returns all active plans', function () {

    $activePlan1 = Plan::factory()->create([
        'status' => PlanStatus::ACTIVE,
    ]);

    $activePlan2 = Plan::factory()->create([
        'status' => PlanStatus::ACTIVE,
    ]);

    $activePlan3 = Plan::factory()->create([
        'status' => PlanStatus::ACTIVE,
    ]);

    $plans = $this->service->getAvailablePlans();

    expect($plans)
        ->toHaveCount(3)
        ->and($plans->pluck('id')->sort()->values()->all())
        ->toBe(
            collect([
                $activePlan1->id,
                $activePlan2->id,
                $activePlan3->id,
            ])->sort()->values()->all()
        );
});

it('returns only active plans', function () {

    $activePlan = Plan::factory()->create([
        'status' => PlanStatus::ACTIVE,
    ]);

    $inactivePlan = Plan::factory()->create([
        'status' => PlanStatus::INACTIVE,
    ]);

    $archivedPlan = Plan::factory()->create([
        'status' => PlanStatus::ARCHIVED,
    ]);

    $plans = $this->service->getAvailablePlans();

    expect($plans)
        ->toHaveCount(1)
        ->and($plans->first()->id)
        ->toBe($activePlan->id)
        ->and($plans->contains('id', $inactivePlan->id))
        ->toBeFalse()
        ->and($plans->contains('id', $archivedPlan->id))
        ->toBeFalse();
});

it('returns an empty collection when there are no active plans', function () {

    Plan::factory()->create([
        'status' => PlanStatus::INACTIVE,
    ]);

    Plan::factory()->create([
        'status' => PlanStatus::ARCHIVED,
    ]);

    $plans = $this->service->getAvailablePlans();

    expect($plans)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it('returns an empty collection when there are no plans', function () {

    $plans = $this->service->getAvailablePlans();

    expect($plans)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it('returns the correct data for an active plan', function () {

    $plan = Plan::factory()->create([
        'name' => 'Professional',
        'monthly_price' => 250.50,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::ACTIVE,
    ]);

    $result = $this->service
        ->getAvailablePlans()
        ->firstWhere('id', $plan->id);

    expect($result)
        ->not->toBeNull();

    expect($result->id)
        ->toBe($plan->id)
        ->and($result->name)
        ->toBe('Professional')
        ->and((float) $result->monthly_price)
        ->toBe(250.50)
        ->and((int) $result->max_doctors)
        ->toBe(20)
        ->and((int) $result->monthly_appointments_limit)
        ->toBe(1000)
        ->and($result->status)
        ->toBe(PlanStatus::ACTIVE);
});

it('returns only the selected columns', function () {

    Plan::factory()->create([
        'status' => PlanStatus::ACTIVE,
    ]);

    $plan = $this->service
        ->getAvailablePlans()
        ->first();

    expect(array_keys($plan->getAttributes()))
        ->toEqualCanonicalizing([
            'id',
            'name',
            'monthly_price',
            'max_doctors',
            'monthly_appointments_limit',
            'status',
        ]);
});

it('does not return created_at or updated_at', function () {

    Plan::factory()->create([
        'status' => PlanStatus::ACTIVE,
    ]);

    $plan = $this->service
        ->getAvailablePlans()
        ->first();

    expect($plan->getAttributes())
        ->not->toHaveKey('created_at')
        ->and($plan->getAttributes())
        ->not->toHaveKey('updated_at');
});

it('returns an eloquent collection of plan models', function () {

    Plan::factory()->count(3)->create([
        'status' => PlanStatus::ACTIVE,
    ]);

    $plans = $this->service->getAvailablePlans();

    expect($plans)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3);

    foreach ($plans as $plan) {
        expect($plan)->toBeInstanceOf(Plan::class);
    }
});

it('returns the correct number of active plans when mixed with inactive and archived plans', function () {

    Plan::factory()->count(3)->create([
        'status' => PlanStatus::ACTIVE,
    ]);

    Plan::factory()->count(5)->create([
        'status' => PlanStatus::INACTIVE,
    ]);

    Plan::factory()->count(4)->create([
        'status' => PlanStatus::ARCHIVED,
    ]);

    $plans = $this->service->getAvailablePlans();

    expect($plans)->toHaveCount(3);
});
it('does not return inactive or archived plans even when their other data is identical', function () {

    $activePlan = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $inactivePlan = Plan::factory()->create([
        'name' => 'Professional',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::INACTIVE,
    ]);

    $archivedPlan = Plan::factory()->create([
        'name' => 'Enterprise',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ARCHIVED,
    ]);

    $plans = $this->service->getAvailablePlans();

    expect($plans)
        ->toHaveCount(1)
        ->and($plans->first()->id)
        ->toBe($activePlan->id)
        ->and($plans->contains('id', $inactivePlan->id))
        ->toBeFalse()
        ->and($plans->contains('id', $archivedPlan->id))
        ->toBeFalse();
});

it('preserves the data of every active plan', function () {

    $basic = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 50,
        'max_doctors' => 3,
        'monthly_appointments_limit' => 300,
        'status' => PlanStatus::ACTIVE,
    ]);

    $professional = Plan::factory()->create([
        'name' => 'Professional',
        'monthly_price' => 150,
        'max_doctors' => 10,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::ACTIVE,
    ]);

    $plans = $this->service->getAvailablePlans();

    $basicResult = $plans->firstWhere('id', $basic->id);
    $professionalResult = $plans->firstWhere('id', $professional->id);

    expect($basicResult)
        ->not->toBeNull()
        ->and($basicResult->name)
        ->toBe('Basic')
        ->and((float) $basicResult->monthly_price)
        ->toBe(50.0)
        ->and((int) $basicResult->max_doctors)
        ->toBe(3)
        ->and((int) $basicResult->monthly_appointments_limit)
        ->toBe(300);

    expect($professionalResult)
        ->not->toBeNull()
        ->and($professionalResult->name)
        ->toBe('Professional')
        ->and((float) $professionalResult->monthly_price)
        ->toBe(150.0)
        ->and((int) $professionalResult->max_doctors)
        ->toBe(10)
        ->and((int) $professionalResult->monthly_appointments_limit)
        ->toBe(1000);
});
