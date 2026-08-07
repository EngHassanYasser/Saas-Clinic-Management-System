<?php

use App\Enums\PlanStatus;
use App\Models\Plan;
use App\Services\Plan\PlanQueryService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->service = app(PlanQueryService::class);
});


it('returns all plans', function () {

    $plan1 = Plan::factory()->create([
        'name' => 'Basic',
    ]);

    $plan2 = Plan::factory()->create([
        'name' => 'Professional',
    ]);

    $plan3 = Plan::factory()->create([
        'name' => 'Enterprise',
    ]);

    $plans = $this->service->getAll();

    expect($plans)
        ->toHaveCount(3)
        ->and($plans->pluck('id')->sort()->values()->all())
        ->toBe(
            collect([
                $plan1->id,
                $plan2->id,
                $plan3->id,
            ])->sort()->values()->all()
        );
});


it('returns active, inactive and archived plans', function () {

    $activePlan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => PlanStatus::ACTIVE,
    ]);

    $inactivePlan = Plan::factory()->create([
        'name' => 'Professional',
        'status' => PlanStatus::INACTIVE,
    ]);

    $archivedPlan = Plan::factory()->create([
        'name' => 'Enterprise',
        'status' => PlanStatus::ARCHIVED,
    ]);

    $plans = $this->service->getAll();

    expect($plans)
        ->toHaveCount(3)
        ->and($plans->pluck('id')->all())
        ->toContain(
            $activePlan->id,
            $inactivePlan->id,
            $archivedPlan->id
        );
});


it('does not filter plans by status', function () {

    $activePlan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => PlanStatus::ACTIVE,
    ]);

    $inactivePlan = Plan::factory()->create([
        'name' => 'Professional',
        'status' => PlanStatus::INACTIVE,
    ]);

    $archivedPlan = Plan::factory()->create([
        'name' => 'Enterprise',
        'status' => PlanStatus::ARCHIVED,
    ]);

    $plans = $this->service->getAll();

    expect($plans->pluck('status')->all())
        ->toContain(
            PlanStatus::ACTIVE,
            PlanStatus::INACTIVE,
            PlanStatus::ARCHIVED
        );
});


it('returns an empty collection when there are no plans', function () {

    $plans = $this->service->getAll();

    expect($plans)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});


it('returns an eloquent collection', function () {

    Plan::factory()->count(3)->create();

    $plans = $this->service->getAll();

    expect($plans)
        ->toBeInstanceOf(Collection::class);
});


it('returns Plan models', function () {

    Plan::factory()->count(3)->create();

    $plans = $this->service->getAll();

    foreach ($plans as $plan) {
        expect($plan)
            ->toBeInstanceOf(Plan::class);
    }
});


it('returns the correct plan data', function () {

    $plan = Plan::factory()->create([
        'name' => 'Professional',
        'monthly_price' => 250.50,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::ACTIVE,
    ]);

    $result = $this->service
        ->getAll()
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
        'name' => 'Basic',
    ]);

    $plan = $this->service
        ->getAll()
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


it('does not return created_at and updated_at', function () {

    Plan::factory()->create([
        'name' => 'Basic',
    ]);

    $plan = $this->service
        ->getAll()
        ->first();

    expect($plan->getAttributes())
        ->not->toHaveKey('created_at')
        ->and($plan->getAttributes())
        ->not->toHaveKey('updated_at');
});


it('returns all plans regardless of their status', function () {

    $activePlan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => PlanStatus::ACTIVE,
    ]);

    $inactivePlan = Plan::factory()->create([
        'name' => 'Professional',
        'status' => PlanStatus::INACTIVE,
    ]);

    $archivedPlan = Plan::factory()->create([
        'name' => 'Enterprise',
        'status' => PlanStatus::ARCHIVED,
    ]);

    $plans = $this->service->getAll();

    expect($plans)
        ->toHaveCount(3)
        ->and($plans->contains('id', $activePlan->id))
        ->toBeTrue()
        ->and($plans->contains('id', $inactivePlan->id))
        ->toBeTrue()
        ->and($plans->contains('id', $archivedPlan->id))
        ->toBeTrue();
});


it('returns all plans when only one plan exists', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => PlanStatus::ACTIVE,
    ]);

    $plans = $this->service->getAll();

    expect($plans)
        ->toHaveCount(1)
        ->and($plans->first()->id)
        ->toBe($plan->id);
});


it('preserves the data of all plans', function () {

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
        'status' => PlanStatus::INACTIVE,
    ]);

    $enterprise = Plan::factory()->create([
        'name' => 'Enterprise',
        'monthly_price' => 500,
        'max_doctors' => 50,
        'monthly_appointments_limit' => 5000,
        'status' => PlanStatus::ARCHIVED,
    ]);

    $plans = $this->service->getAll();

    $basicResult = $plans->firstWhere('id', $basic->id);
    $professionalResult = $plans->firstWhere('id', $professional->id);
    $enterpriseResult = $plans->firstWhere('id', $enterprise->id);

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

    expect($enterpriseResult)
        ->not->toBeNull()
        ->and($enterpriseResult->name)
        ->toBe('Enterprise')
        ->and((float) $enterpriseResult->monthly_price)
        ->toBe(500.0)
        ->and((int) $enterpriseResult->max_doctors)
        ->toBe(50)
        ->and((int) $enterpriseResult->monthly_appointments_limit)
        ->toBe(5000);
});
