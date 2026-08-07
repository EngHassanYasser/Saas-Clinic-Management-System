<?php

use App\Models\Clinic;
use App\Models\Subscription;
use App\Services\Clinic\ClinicStatisticsService;

beforeEach(function () {
    $this->service = app(ClinicStatisticsService::class);
});

it('returns zero statistics when there are no subscriptions', function () {

    $stats = $this->service->getStats();

    expect($stats->total)->toBe(0)
        ->and($stats->pending)->toBe(0)
        ->and($stats->active)->toBe(0)
        ->and($stats->inactive)->toBe(0);
});

it('counts subscriptions correctly', function () {

    Subscription::factory()->pending()->create();
    Subscription::factory()->active()->create();
    Subscription::factory()->expired()->create();
    Subscription::factory()->cancelled()->create();

    $stats = $this->service->getStats();

    expect($stats->total)->toBe(4)
        ->and($stats->pending)->toBe(1)
        ->and($stats->active)->toBe(1)
        ->and($stats->inactive)->toBe(2);
});

it('counts only latest subscription for each clinic', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    Subscription::factory()->pending()->create([
        'clinic_id' => $clinic1->id,
    ]);

    Subscription::factory()->active()->create([
        'clinic_id' => $clinic1->id,
    ]);

    Subscription::factory()->cancelled()->create([
        'clinic_id' => $clinic2->id,
    ]);

    Subscription::factory()->expired()->create([
        'clinic_id' => $clinic2->id,
    ]);

    $stats = $this->service->getStats();

    expect($stats->total)->toBe(2)
        ->and($stats->pending)->toBe(0)
        ->and($stats->active)->toBe(1)
        ->and($stats->inactive)->toBe(1);
});

it('ignores subscriptions without clinic id', function () {

    Subscription::factory()->active()->create([
        'clinic_id' => null,
    ]);

    Subscription::factory()->pending()->create([
        'clinic_id' => null,
    ]);

    $stats = $this->service->getStats();

    expect($stats->total)->toBe(0)
        ->and($stats->pending)->toBe(0)
        ->and($stats->active)->toBe(0)
        ->and($stats->inactive)->toBe(0);
});

it('counts inactive as expired plus cancelled', function () {

    Subscription::factory()->expired()->create();

    Subscription::factory()->cancelled()->create();

    Subscription::factory()->active()->create();

    $stats = $this->service->getStats();

    expect($stats->inactive)->toBe(2)
        ->and($stats->active)->toBe(1)
        ->and($stats->pending)->toBe(0);
});

it('returns integer values', function () {

    Subscription::factory()->active()->create();

    $stats = $this->service->getStats();

    expect($stats->total)->toBeInt()
        ->and($stats->pending)->toBeInt()
        ->and($stats->active)->toBeInt()
        ->and($stats->inactive)->toBeInt();
});

it('uses latest subscription instead of old one', function () {

    $clinic = Clinic::factory()->create();

    Subscription::factory()->pending()->create([
        'clinic_id' => $clinic->id,
    ]);

    Subscription::factory()->active()->create([
        'clinic_id' => $clinic->id,
    ]);

    Subscription::factory()->expired()->create([
        'clinic_id' => $clinic->id,
    ]);

    $stats = $this->service->getStats();

    expect($stats->total)->toBe(1)
        ->and($stats->pending)->toBe(0)
        ->and($stats->active)->toBe(0)
        ->and($stats->inactive)->toBe(1);
});

it('returns correct statistics with mixed clinics', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();
    $clinic3 = Clinic::factory()->create();

    Subscription::factory()->active()->create([
        'clinic_id' => $clinic1->id,
    ]);

    Subscription::factory()->pending()->create([
        'clinic_id' => $clinic2->id,
    ]);

    Subscription::factory()->cancelled()->create([
        'clinic_id' => $clinic3->id,
    ]);

    $stats = $this->service->getStats();

    expect($stats->total)->toBe(3)
        ->and($stats->active)->toBe(1)
        ->and($stats->pending)->toBe(1)
        ->and($stats->inactive)->toBe(1);
});