<?php

use App\Enums\ComplainStatus;
use App\Models\Clinic;
use App\Models\Complain;
use App\Services\Complain\ComplainStatisticsService;

beforeEach(function () {
    $this->service = app(ComplainStatisticsService::class);
});

it('returns correct complain statistics', function () {

    $clinic = Clinic::factory()->create();

    Complain::factory(2)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplainStatus::PENDING,
    ]);

    Complain::factory(3)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplainStatus::UNDER_REVIEW,
    ]);

    Complain::factory(4)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplainStatus::RESOLVED,
    ]);

    Complain::factory()->create([
        'clinic_id' => $clinic->id,
        'status' => ComplainStatus::REJECTED,
    ]);

    $statistics = $this->service->getStatistics($clinic->id);

    expect($statistics['total'])->toBe(10)
        ->and($statistics['pending'])->toBe(2)
        ->and($statistics['under_review'])->toBe(3)
        ->and($statistics['resolved'])->toBe(4)
        ->and($statistics['rejected'])->toBe(1);

});
it('counts only complains of requested clinic', function () {

    $clinic = Clinic::factory()->create();

    $otherClinic = Clinic::factory()->create();

    Complain::factory(5)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplainStatus::PENDING,
    ]);

    Complain::factory(100)->create([
        'clinic_id' => $otherClinic->id,
        'status' => ComplainStatus::PENDING,
    ]);

    $statistics = $this->service->getStatistics($clinic->id);

    expect($statistics['total'])->toBe(5)
        ->and($statistics['pending'])->toBe(5);

});
it('returns zeros when clinic has no complains', function () {

    $clinic = Clinic::factory()->create();

    $statistics = $this->service->getStatistics($clinic->id);

    expect($statistics['total'])->toBe(0)
        ->and($statistics['pending'])->toBe(0)
        ->and($statistics['under_review'])->toBe(0)
        ->and($statistics['resolved'])->toBe(0)
        ->and($statistics['rejected'])->toBe(0);

});
it('returns integer statistics', function () {

    $clinic = Clinic::factory()->create();

    Complain::factory()->create([
        'clinic_id' => $clinic->id,
    ]);

    $statistics = $this->service->getStatistics($clinic->id);

    expect($statistics['total'])->toBeInt()
        ->and($statistics['pending'])->toBeInt()
        ->and($statistics['under_review'])->toBeInt()
        ->and($statistics['resolved'])->toBeInt()
        ->and($statistics['rejected'])->toBeInt();

});
it('total equals sum of all statuses', function () {

    $clinic = Clinic::factory()->create();

    Complain::factory(2)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplainStatus::PENDING,
    ]);

    Complain::factory(3)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplainStatus::RESOLVED,
    ]);

    Complain::factory()->create([
        'clinic_id' => $clinic->id,
        'status' => ComplainStatus::REJECTED,
    ]);

    $statistics = $this->service->getStatistics($clinic->id);

    expect($statistics['total'])
        ->toBe(
            $statistics['pending']
            + $statistics['under_review']
            + $statistics['resolved']
            + $statistics['rejected']
        );

});
it('returns complain model instance', function () {

    $clinic = Clinic::factory()->create();

    $statistics = $this->service->getStatistics($clinic->id);

    expect($statistics)->toBeArray();

});