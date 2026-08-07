<?php

use App\Enums\PlanStatus;
use App\Models\Plan;
use App\Services\Plan\PlanService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->service = app(PlanService::class);
});

it('updates a plan successfully', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $result = $this->service->update([
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::INACTIVE,
    ], $plan->id);

    expect($result)
        ->toBeTrue();

    $plan->refresh();

    expect($plan->name)
        ->toBe('Professional')
        ->and((float) $plan->monthly_price)
        ->toBe(250.0)
        ->and((int) $plan->max_doctors)
        ->toBe(20)
        ->and((int) $plan->monthly_appointments_limit)
        ->toBe(1000)
        ->and($plan->status)
        ->toBe(PlanStatus::INACTIVE);
});

it('persists updated plan data in the database', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $this->service->update([
        'name' => 'Professional',
        'monthly_price' => 299.99,
        'max_doctors' => 15,
        'monthly_appointments_limit' => 1500,
        'status' => PlanStatus::ARCHIVED,
    ], $plan->id);

    $this->assertDatabaseHas('plans', [
        'id' => $plan->id,
        'name' => 'Professional',
        'monthly_price' => 299.99,
        'max_doctors' => 15,
        'monthly_appointments_limit' => 1500,
        'status' => PlanStatus::ARCHIVED->value,
    ]);
});

it('updates all supported fields', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $data = [
        'name' => 'Enterprise',
        'monthly_price' => 999.99,
        'max_doctors' => 50,
        'monthly_appointments_limit' => 5000,
        'status' => PlanStatus::ARCHIVED,
    ];

    $this->service->update($data, $plan->id);

    $plan->refresh();

    expect($plan->name)
        ->toBe($data['name'])
        ->and((float) $plan->monthly_price)
        ->toBe(999.99)
        ->and((int) $plan->max_doctors)
        ->toBe(50)
        ->and((int) $plan->monthly_appointments_limit)
        ->toBe(5000)
        ->and($plan->status)
        ->toBe(PlanStatus::ARCHIVED);
});

it('can change status from active to inactive', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => PlanStatus::ACTIVE,
    ]);

    $result = $this->service->update([
        'name' => 'Basic',
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => PlanStatus::INACTIVE,
    ], $plan->id);

    expect($result)
        ->toBeTrue();

    expect($plan->refresh()->status)
        ->toBe(PlanStatus::INACTIVE);
});

it('can change status from inactive to active', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => PlanStatus::INACTIVE,
    ]);

    $result = $this->service->update([
        'name' => 'Basic',
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => PlanStatus::ACTIVE,
    ], $plan->id);

    expect($result)
        ->toBeTrue();

    expect($plan->refresh()->status)
        ->toBe(PlanStatus::ACTIVE);
});

it('can change status from active to archived', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => PlanStatus::ACTIVE,
    ]);

    $this->service->update([
        'name' => 'Basic',
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => PlanStatus::ARCHIVED,
    ], $plan->id);

    expect($plan->refresh()->status)
        ->toBe(PlanStatus::ARCHIVED);
});

it('can change status from archived to active', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => PlanStatus::ARCHIVED,
    ]);

    $this->service->update([
        'name' => 'Basic',
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => PlanStatus::ACTIVE,
    ], $plan->id);

    expect($plan->refresh()->status)
        ->toBe(PlanStatus::ACTIVE);
});

it('can update the monthly price without changing other fields', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $this->service->update([
        'name' => $plan->name,
        'monthly_price' => 199.99,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => $plan->status,
    ], $plan->id);

    $plan->refresh();

    expect((float) $plan->monthly_price)
        ->toBe(199.99)
        ->and($plan->name)
        ->toBe('Basic')
        ->and((int) $plan->max_doctors)
        ->toBe(5)
        ->and((int) $plan->monthly_appointments_limit)
        ->toBe(500)
        ->and($plan->status)
        ->toBe(PlanStatus::ACTIVE);
});

it('can update max doctors without changing other fields', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $this->service->update([
        'name' => $plan->name,
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => 25,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => $plan->status,
    ], $plan->id);

    $plan->refresh();

    expect((int) $plan->max_doctors)
        ->toBe(25)
        ->and($plan->name)
        ->toBe('Basic')
        ->and((float) $plan->monthly_price)
        ->toBe(100.0)
        ->and((int) $plan->monthly_appointments_limit)
        ->toBe(500)
        ->and($plan->status)
        ->toBe(PlanStatus::ACTIVE);
});

it('can update monthly appointments limit without changing other fields', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $this->service->update([
        'name' => $plan->name,
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => 1500,
        'status' => $plan->status,
    ], $plan->id);

    $plan->refresh();

    expect((int) $plan->monthly_appointments_limit)
        ->toBe(1500)
        ->and($plan->name)
        ->toBe('Basic')
        ->and((float) $plan->monthly_price)
        ->toBe(100.0)
        ->and((int) $plan->max_doctors)
        ->toBe(5)
        ->and($plan->status)
        ->toBe(PlanStatus::ACTIVE);
});

it('does not modify other plans', function () {

    $plan1 = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $plan2 = Plan::factory()->create([
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::INACTIVE,
    ]);

    $this->service->update([
        'name' => 'Enterprise',
        'monthly_price' => 500,
        'max_doctors' => 50,
        'monthly_appointments_limit' => 5000,
        'status' => PlanStatus::ARCHIVED,
    ], $plan1->id);

    $plan2->refresh();

    expect($plan2->name)
        ->toBe('Professional')
        ->and((float) $plan2->monthly_price)
        ->toBe(250.0)
        ->and((int) $plan2->max_doctors)
        ->toBe(20)
        ->and((int) $plan2->monthly_appointments_limit)
        ->toBe(1000)
        ->and($plan2->status)
        ->toBe(PlanStatus::INACTIVE);
});

it('does not create a new plan when updating an existing plan', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
    ]);

    $countBefore = Plan::count();

    $this->service->update([
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::ACTIVE,
    ], $plan->id);

    expect(Plan::count())
        ->toBe($countBefore);
});

it('keeps the same plan id after update', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
    ]);

    $originalId = $plan->id;

    $this->service->update([
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::ACTIVE,
    ], $plan->id);

    expect($plan->refresh()->id)
        ->toBe($originalId);
});

it('throws ModelNotFoundException when plan does not exist', function () {

    expect(fn () => $this->service->update([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ], 999999999))
        ->toThrow(ModelNotFoundException::class);
});

it('does not create a plan when the target plan does not exist', function () {

    $countBefore = Plan::count();

    expect(fn () => $this->service->update([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ], 999999999))
        ->toThrow(ModelNotFoundException::class);

    expect(Plan::count())
        ->toBe($countBefore);
});

it('throws QueryException when updating to an existing plan name', function () {

    Plan::factory()->create([
        'name' => 'Basic',
    ]);

    $plan = Plan::factory()->create([
        'name' => 'Professional',
    ]);

    expect(fn () => $this->service->update([
        'name' => 'Basic',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::ACTIVE,
    ], $plan->id))
        ->toThrow(QueryException::class);

    $plan->refresh();

    expect($plan->name)
        ->toBe('Professional');
});

it('does not change the plan when an update violates the unique name constraint', function () {

    $existingPlan = Plan::factory()->create([
        'name' => 'Basic',
        'monthly_price' => 100,
        'max_doctors' => 5,
        'monthly_appointments_limit' => 500,
        'status' => PlanStatus::ACTIVE,
    ]);

    $targetPlan = Plan::factory()->create([
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::INACTIVE,
    ]);

    expect(fn () => $this->service->update([
        'name' => 'Basic',
        'monthly_price' => 999,
        'max_doctors' => 99,
        'monthly_appointments_limit' => 5000,
        'status' => PlanStatus::ARCHIVED,
    ], $targetPlan->id))
        ->toThrow(QueryException::class);

    $targetPlan->refresh();

    expect($targetPlan->name)
        ->toBe('Professional')
        ->and((float) $targetPlan->monthly_price)
        ->toBe(250.0)
        ->and((int) $targetPlan->max_doctors)
        ->toBe(20)
        ->and((int) $targetPlan->monthly_appointments_limit)
        ->toBe(1000)
        ->and($targetPlan->status)
        ->toBe(PlanStatus::INACTIVE);

    expect($existingPlan->refresh()->name)
        ->toBe('Basic');
});

it('updates from each supported status to active', function (PlanStatus $currentStatus) {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => $currentStatus,
    ]);

    $this->service->update([
        'name' => 'Basic',
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => PlanStatus::ACTIVE,
    ], $plan->id);

    expect($plan->refresh()->status)
        ->toBe(PlanStatus::ACTIVE);

})->with([
    PlanStatus::ACTIVE,
    PlanStatus::INACTIVE,
    PlanStatus::ARCHIVED,
]);

it('updates from each supported status to inactive', function (PlanStatus $currentStatus) {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => $currentStatus,
    ]);

    $this->service->update([
        'name' => 'Basic',
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => PlanStatus::INACTIVE,
    ], $plan->id);

    expect($plan->refresh()->status)
        ->toBe(PlanStatus::INACTIVE);

})->with([
    PlanStatus::ACTIVE,
    PlanStatus::INACTIVE,
    PlanStatus::ARCHIVED,
]);

it('updates from each supported status to archived', function (PlanStatus $currentStatus) {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => $currentStatus,
    ]);

    $this->service->update([
        'name' => 'Basic',
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => PlanStatus::ARCHIVED,
    ], $plan->id);

    $plan->refresh();

    expect($plan->status)
        ->toBe(PlanStatus::ARCHIVED);

})->with([
    PlanStatus::ACTIVE,
    PlanStatus::INACTIVE,
    PlanStatus::ARCHIVED,
]);

it('returns true when the plan was successfully updated', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
    ]);

    $result = $this->service->update([
        'name' => 'Professional',
        'monthly_price' => 250,
        'max_doctors' => 20,
        'monthly_appointments_limit' => 1000,
        'status' => PlanStatus::ACTIVE,
    ], $plan->id);

    expect($result)
        ->toBeTrue();
});

it('keeps the same number of plans after a successful update', function () {

    Plan::factory()->create([
        'name' => 'Basic',
    ]);

    Plan::factory()->create([
        'name' => 'Professional',
    ]);

    Plan::factory()->create([
        'name' => 'Enterprise',
    ]);

    $countBefore = Plan::count();

    $plan = Plan::where('name', 'Basic')->firstOrFail();

    $this->service->update([
        'name' => 'Starter',
        'monthly_price' => 150,
        'max_doctors' => 7,
        'monthly_appointments_limit' => 700,
        'status' => PlanStatus::ACTIVE,
    ], $plan->id);

    expect(Plan::count())
        ->toBe($countBefore);
});
it('debugs inactive to archived update', function () {

    $plan = Plan::factory()->create([
        'name' => 'Basic',
        'status' => PlanStatus::INACTIVE,
    ]);

    expect($plan->status)
        ->toBe(PlanStatus::INACTIVE);

    $result = $this->service->update([
        'name' => 'Basic',
        'monthly_price' => $plan->monthly_price,
        'max_doctors' => $plan->max_doctors,
        'monthly_appointments_limit' => $plan->monthly_appointments_limit,
        'status' => PlanStatus::ARCHIVED,
    ], $plan->id);

    expect($result)
        ->toBeTrue();

    $plan->refresh();

    dump([
        'result' => $result,
        'status' => $plan->status,
        'status_value' => $plan->status->value,
        'database_status' => DB::table('plans')
            ->where('id', $plan->id)
            ->value('status'),
    ]);

    expect($plan->status)
        ->toBe(PlanStatus::ARCHIVED);
});