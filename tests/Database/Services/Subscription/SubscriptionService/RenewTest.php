<?php

use App\Enums\SubscriptionStatus;
use App\Exceptions\ActiveSubscriptionAlreadyExistsException;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\Subscription\SubscriptionService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(SubscriptionService::class);
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function makeRenewSubscriptionContext(array $subscriptionOverrides = []): array
{
    $clinic = Clinic::factory()->create();

    $plan = Plan::factory()->create();

    $subscription = Subscription::factory()->create(array_merge([
        'clinic_id' => $clinic->id,
        'plan_id' => $plan->id,
        'start_at' => '2026-01-01',
        'end_at' => '2026-02-01',
        'status' => SubscriptionStatus::ACTIVE->value,
    ], $subscriptionOverrides));

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

it('returns a boolean when renewal succeeds', function () {
    $context = makeRenewSubscriptionContext([
        'status' => SubscriptionStatus::ACTIVE->value,
    ]);

    $result = $this->service->renew(
        $context['subscription']->id
    );

    expect($result)
        ->toBeBool();
});

/*
|--------------------------------------------------------------------------
| Successful renewal
|--------------------------------------------------------------------------
*/

it('successfully renews the subscription', function () {
    $context = makeRenewSubscriptionContext([
        'start_at' => '2025-01-01',
        'end_at' => '2025-02-01',
        'status' => SubscriptionStatus::ACTIVE->value,
    ]);

    $result = $this->service->renew(
        $context['subscription']->id
    );

    expect($result)
        ->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Database persistence
|--------------------------------------------------------------------------
*/

it('persists the renewal changes in the database', function () {
    $context = makeRenewSubscriptionContext([
        'start_at' => '2025-01-01',
        'end_at' => '2025-02-01',
        'status' => SubscriptionStatus::ACTIVE,
    ]);

    $this->service->renew(
        $context['subscription']->id
    );

    $subscription = Subscription::findOrFail(
        $context['subscription']->id
    );

    expect($subscription->start_at)
        ->toBe(now()->toDateString());

    expect($subscription->end_at)
        ->toBe(now()->addMonth()->toDateString());

    expect($subscription->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});

/*
|--------------------------------------------------------------------------
| Start date
|--------------------------------------------------------------------------
*/

it('sets start_at to the current date', function () {
    $context = makeRenewSubscriptionContext([
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    $this->service->renew(
        $context['subscription']->id
    );

    $subscription = $context['subscription']->fresh();

    expect($subscription->start_at)
        ->toBe(now()->toDateString());
});

/*
|--------------------------------------------------------------------------
| End date
|--------------------------------------------------------------------------
*/

it('sets end_at exactly one month after the renewal start date', function () {
    $context = makeRenewSubscriptionContext([
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    $this->service->renew(
        $context['subscription']->id
    );

    $subscription = $context['subscription']->fresh();

    expect($subscription->end_at)
        ->toBe(
            now()
                ->addMonth()
                ->toDateString()
        );
});

/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

it('sets the subscription status to active after renewal', function () {
    $context = makeRenewSubscriptionContext([
        'status' => SubscriptionStatus::ACTIVE,
    ]);

    $this->service->renew(
        $context['subscription']->id
    );

    $subscription = $context['subscription']->fresh();

    expect($subscription->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});

/*
|--------------------------------------------------------------------------
| Inactive subscription
|--------------------------------------------------------------------------
*/

it('can renew an inactive subscription', function () {
    $context = makeRenewSubscriptionContext([
        'status' => SubscriptionStatus::INACTIVE,
        'start_at' => '2025-01-01',
        'end_at' => '2025-02-01',
    ]);

    $result = $this->service->renew(
        $context['subscription']->id
    );

    expect($result)
        ->toBeTrue();

    $subscription = $context['subscription']->fresh();

    expect($subscription->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});

/*
|--------------------------------------------------------------------------
| Same subscription should not block itself
|--------------------------------------------------------------------------
*/

it('does not consider the current subscription as another active subscription', function () {
    $context = makeRenewSubscriptionContext([
        'status' => SubscriptionStatus::ACTIVE,
    ]);

    $result = $this->service->renew(
        $context['subscription']->id
    );

    expect($result)
        ->toBeTrue();

    expect(
        $context['subscription']->fresh()->status
    )->toBe(SubscriptionStatus::ACTIVE);
});

/*
|--------------------------------------------------------------------------
| Existing active subscription
|--------------------------------------------------------------------------
*/

it('throws when another active subscription exists for the same clinic', function () {
    $context = makeRenewSubscriptionContext([
        'status' => SubscriptionStatus::INACTIVE,
        'start_at' => '2025-01-01',
        'end_at' => '2025-02-01',
    ]);

    $otherSubscription = Subscription::factory()->create([
        'clinic_id' => $context['clinic']->id,
        'plan_id' => $context['plan']->id,
        'status' => SubscriptionStatus::ACTIVE,
        'start_at' => now()->subDays(10)->toDateString(),
        'end_at' => now()->addDays(20)->toDateString(),
    ]);

    expect(fn () => $this->service->renew(
        $context['subscription']->id
    ))->toThrow(
        ActiveSubscriptionAlreadyExistsException::class
    );

    expect($otherSubscription->fresh()->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});

/*
|--------------------------------------------------------------------------
| Existing active subscription does not belong to another clinic
|--------------------------------------------------------------------------
*/

it('allows renewal when the active subscription belongs to another clinic', function () {
    $context = makeRenewSubscriptionContext([
        'status' => SubscriptionStatus::INACTIVE,
        'start_at' => '2025-01-01',
        'end_at' => '2025-02-01',
    ]);

    $anotherClinic = Clinic::factory()->create();

    Subscription::factory()->create([
        'clinic_id' => $anotherClinic->id,
        'plan_id' => $context['plan']->id,
        'status' => SubscriptionStatus::ACTIVE->value,
        'start_at' => now()->subDays(10)->toDateString(),
        'end_at' => now()->addDays(20)->toDateString(),
    ]);

    $result = $this->service->renew(
        $context['subscription']->id
    );

    expect($result)
        ->toBeTrue();

    expect($context['subscription']->fresh()->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});

/*
|--------------------------------------------------------------------------
| No new subscription
|--------------------------------------------------------------------------
*/

it('does not create a new subscription when renewing', function () {
    $context = makeRenewSubscriptionContext();

    $before = Subscription::count();

    $this->service->renew(
        $context['subscription']->id
    );

    expect(Subscription::count())
        ->toBe($before);
});

/*
|--------------------------------------------------------------------------
| Same ID
|--------------------------------------------------------------------------
*/

it('updates the existing subscription instead of creating another record', function () {
    $context = makeRenewSubscriptionContext();

    $id = $context['subscription']->id;

    $this->service->renew($id);

    expect(
        Subscription::whereKey($id)->count()
    )->toBe(1);

    expect(
        Subscription::count()
    )->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Clinic ID must remain unchanged
|--------------------------------------------------------------------------
*/

it('does not change the clinic_id during renewal', function () {
    $context = makeRenewSubscriptionContext();

    $clinicId = $context['clinic']->id;

    $this->service->renew(
        $context['subscription']->id
    );

    $subscription = $context['subscription']->fresh();

    expect($subscription->clinic_id)
        ->toBe($clinicId);
});

/*
|--------------------------------------------------------------------------
| Plan ID must remain unchanged
|--------------------------------------------------------------------------
*/

it('does not change the plan_id during renewal', function () {
    $context = makeRenewSubscriptionContext();

    $planId = $context['plan']->id;

    $this->service->renew(
        $context['subscription']->id
    );

    $subscription = $context['subscription']->fresh();

    expect($subscription->plan_id)
        ->toBe($planId);
});

/*
|--------------------------------------------------------------------------
| ID must remain unchanged
|--------------------------------------------------------------------------
*/

it('does not change the subscription id during renewal', function () {
    $context = makeRenewSubscriptionContext();

    $id = $context['subscription']->id;

    $this->service->renew($id);

    $subscription = Subscription::findOrFail($id);

    expect($subscription->id)
        ->toBe($id);
});

/*
|--------------------------------------------------------------------------
| Existing dates are replaced
|--------------------------------------------------------------------------
*/

it('replaces the old start_at date', function () {
    $context = makeRenewSubscriptionContext([
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    $this->service->renew(
        $context['subscription']->id
    );

    $subscription = $context['subscription']->fresh();

    expect($subscription->start_at)
        ->not->toBe('2020-01-01')
        ->toBe(now()->toDateString());
});

/*
|--------------------------------------------------------------------------
| Existing end date is replaced
|--------------------------------------------------------------------------
*/

it('replaces the old end_at date', function () {
    $context = makeRenewSubscriptionContext([
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    $this->service->renew(
        $context['subscription']->id
    );

    $subscription = $context['subscription']->fresh();

    expect($subscription->end_at)
        ->not->toBe('2020-02-01')
        ->toBe(now()->addMonth()->toDateString());
});

/*
|--------------------------------------------------------------------------
| Multiple subscriptions in different clinics
|--------------------------------------------------------------------------
*/

it('renews only the requested subscription', function () {
    $context = makeRenewSubscriptionContext([
        'status' => SubscriptionStatus::INACTIVE,
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    $anotherClinic = Clinic::factory()->create();

    $otherSubscription = Subscription::factory()->create([
        'clinic_id' => $anotherClinic->id,
        'plan_id' => $context['plan']->id,
        'status' => SubscriptionStatus::INACTIVE,
        'start_at' => '2020-03-01',
        'end_at' => '2020-04-01',
    ]);

    $this->service->renew(
        $context['subscription']->id
    );

    $target = $context['subscription']->fresh();
    $other = $otherSubscription->fresh();

    expect($target->start_at)
        ->toBe(now()->toDateString());

    expect($target->end_at)
        ->toBe(now()->addMonth()->toDateString());

    expect($other->start_at)
        ->toBe('2020-03-01');

    expect($other->end_at)
        ->toBe('2020-04-01');

    expect($other->status)
        ->toBe(SubscriptionStatus::INACTIVE);
});

/*
|--------------------------------------------------------------------------
| Multiple subscriptions same clinic
|--------------------------------------------------------------------------
*/

it('does not renew when another active subscription exists in the same clinic', function () {
    $context = makeRenewSubscriptionContext([
        'status' => SubscriptionStatus::INACTIVE,
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    $otherSubscription = Subscription::factory()->create([
        'clinic_id' => $context['clinic']->id,
        'plan_id' => $context['plan']->id,
        'status' => SubscriptionStatus::ACTIVE,
        'start_at' => now()->subDays(10)->toDateString(),
        'end_at' => now()->addDays(20)->toDateString(),
    ]);

    expect(fn () => $this->service->renew(
        $context['subscription']->id
    ))->toThrow(
        ActiveSubscriptionAlreadyExistsException::class
    );

    $target = $context['subscription']->fresh();

    expect($target->start_at)
        ->toBe('2020-01-01');

    expect($target->end_at)
        ->toBe('2020-02-01');

    expect($target->status)
        ->toBe(SubscriptionStatus::INACTIVE);

    expect($otherSubscription->fresh()->status)
        ->toBe(SubscriptionStatus::ACTIVE);
});

/*
|--------------------------------------------------------------------------
| Database count remains unchanged on validation failure
|--------------------------------------------------------------------------
*/

it('does not create any subscription when renewal validation fails', function () {
    $context = makeRenewSubscriptionContext([
        'status' => 'inactive',
    ]);

    Subscription::factory()->create([
        'clinic_id' => $context['clinic']->id,
        'plan_id' => $context['plan']->id,
        'status' => SubscriptionStatus::ACTIVE->value,
        'start_at' => now()->subDays(10)->toDateString(),
        'end_at' => now()->addDays(20)->toDateString(),
    ]);

    $before = Subscription::count();

    expect(fn () => $this->service->renew(
        $context['subscription']->id
    ))->toThrow(
        ActiveSubscriptionAlreadyExistsException::class
    );

    expect(Subscription::count())
        ->toBe($before);
});

/*
|--------------------------------------------------------------------------
| Transaction rollback
|--------------------------------------------------------------------------
*/

it('does not leave partial changes when renewal validation fails', function () {
    $context = makeRenewSubscriptionContext([
        'status' => 'inactive',
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    Subscription::factory()->create([
        'clinic_id' => $context['clinic']->id,
        'plan_id' => $context['plan']->id,
        'status' => SubscriptionStatus::ACTIVE->value,
        'start_at' => now()->subDays(10)->toDateString(),
        'end_at' => now()->addDays(20)->toDateString(),
    ]);

    $before = $context['subscription']->fresh();

    expect(fn () => $this->service->renew(
        $context['subscription']->id
    ))->toThrow(
        ActiveSubscriptionAlreadyExistsException::class
    );

    $after = $context['subscription']->fresh();

    expect($after->start_at)
        ->toBe($before->start_at);

    expect($after->end_at)
        ->toBe($before->end_at);

    expect($after->status)
        ->toBe($before->status);
});

/*
|--------------------------------------------------------------------------
| Non-existing subscription
|--------------------------------------------------------------------------
*/

it('throws ModelNotFoundException when the subscription does not exist', function () {
    expect(fn () => $this->service->renew(999999))
        ->toThrow(ModelNotFoundException::class);
});

/*
|--------------------------------------------------------------------------
| Does not modify other subscriptions
|--------------------------------------------------------------------------
*/

it('does not modify unrelated subscriptions', function () {
    $context = makeRenewSubscriptionContext([
        'status' => 'inactive',
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    $anotherClinic = Clinic::factory()->create();

    $otherSubscription = Subscription::factory()->create([
        'clinic_id' => $anotherClinic->id,
        'plan_id' => $context['plan']->id,
        'status' => 'inactive',
        'start_at' => '2021-01-01',
        'end_at' => '2021-02-01',
    ]);

    $original = $otherSubscription->fresh();

    $this->service->renew(
        $context['subscription']->id
    );

    $otherSubscription->refresh();

    expect($otherSubscription->start_at)
        ->toBe($original->start_at);

    expect($otherSubscription->end_at)
        ->toBe($original->end_at);

    expect($otherSubscription->status)
        ->toBe($original->status);

    expect($otherSubscription->clinic_id)
        ->toBe($original->clinic_id);

    expect($otherSubscription->plan_id)
        ->toBe($original->plan_id);
});

/*
|--------------------------------------------------------------------------
| Database row exists after renewal
|--------------------------------------------------------------------------
*/

it('keeps the subscription persisted after renewal', function () {
    $context = makeRenewSubscriptionContext();

    $this->service->renew(
        $context['subscription']->id
    );

    expect(
        DB::table('subscriptions')
            ->where('id', $context['subscription']->id)
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Exact database values
|--------------------------------------------------------------------------
*/

it('stores the exact renewal values in the database', function () {
    $context = makeRenewSubscriptionContext([
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
        'status' => 'inactive',
    ]);

    $this->service->renew(
        $context['subscription']->id
    );

    $row = DB::table('subscriptions')
        ->where('id', $context['subscription']->id)
        ->first();

    expect($row->start_at)
        ->toBe(now()->toDateString());

    expect($row->end_at)
        ->toBe(now()->addMonth()->toDateString());

    expect($row->status)
        ->toBe(SubscriptionStatus::ACTIVE->value);

    expect($row->clinic_id)
        ->toBe($context['clinic']->id);

    expect($row->plan_id)
        ->toBe($context['plan']->id);
});

/*
|--------------------------------------------------------------------------
| Repeated renewal
|--------------------------------------------------------------------------
*/

it('can renew the same subscription again when no other active subscription exists', function () {
    $context = makeRenewSubscriptionContext([
        'status' => 'inactive',
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    $firstResult = $this->service->renew(
        $context['subscription']->id
    );

    expect($firstResult)
        ->toBeTrue();

    /*
     * The current subscription is excluded by its own ID,
     * so renewing it again should still be allowed.
     */
    $secondResult = $this->service->renew(
        $context['subscription']->id
    );

    expect($secondResult)
        ->toBeTrue();

    expect(Subscription::count())
        ->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Query should target requested subscription
|--------------------------------------------------------------------------
*/

it('does not accidentally renew a different subscription', function () {
    $context = makeRenewSubscriptionContext([
        'status' => SubscriptionStatus::INACTIVE,
        'start_at' => '2020-01-01',
        'end_at' => '2020-02-01',
    ]);

    $anotherClinic = Clinic::factory()->create();

    $otherSubscription = Subscription::factory()->create([
        'clinic_id' => $anotherClinic->id,
        'plan_id' => $context['plan']->id,
        'status' => 'inactive',
        'start_at' => '2019-01-01',
        'end_at' => '2019-02-01',
    ]);

    $this->service->renew(
        $context['subscription']->id
    );

    expect(
        $context['subscription']->fresh()->start_at
    )->toBe(now()->toDateString());

    expect(
        $otherSubscription->fresh()->start_at
    )->toBe('2019-01-01');
});