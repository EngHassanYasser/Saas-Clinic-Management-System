<?php

use App\Enums\ComplaintStatus;
use App\Models\Clinic;
use App\Models\Complaint;
use App\Services\Complaint\ComplaintStatisticsService;

beforeEach(function () {
    $this->service = app(ComplaintStatisticsService::class);
});

it('returns correct complaint statistics', function () {

    $clinic = Clinic::factory()->create();

    Complaint::factory(2)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplaintStatus::PENDING,
    ]);

    Complaint::factory(3)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplaintStatus::UNDER_REVIEW,
    ]);

    Complaint::factory(4)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplaintStatus::RESOLVED,
    ]);

    Complaint::factory()->create([
        'clinic_id' => $clinic->id,
        'status' => ComplaintStatus::REJECTED,
    ]);

    $statistics = $this->service->getStatistics($clinic->id);

    expect($statistics['total'])->toBe(10)
        ->and($statistics['pending'])->toBe(2)
        ->and($statistics['under_review'])->toBe(3)
        ->and($statistics['resolved'])->toBe(4)
        ->and($statistics['rejected'])->toBe(1);

});
it('counts only complaintts of requested clinic', function () {

    $clinic = Clinic::factory()->create();

    $otherClinic = Clinic::factory()->create();

    Complaint::factory(5)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplaintStatus::PENDING,
    ]);

    Complaint::factory(100)->create([
        'clinic_id' => $otherClinic->id,
        'status' => ComplaintStatus::PENDING,
    ]);

    $statistics = $this->service->getStatistics($clinic->id);

    expect($statistics['total'])->toBe(5)
        ->and($statistics['pending'])->toBe(5);

});
it('returns zeros when clinic has no complaintts', function () {

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

    Complaint::factory()->create([
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

    Complaint::factory(2)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplaintStatus::PENDING,
    ]);

    Complaint::factory(3)->create([
        'clinic_id' => $clinic->id,
        'status' => ComplaintStatus::RESOLVED,
    ]);

    Complaint::factory()->create([
        'clinic_id' => $clinic->id,
        'status' => ComplaintStatus::REJECTED,
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
it('returns complaint model instance', function () {

    $clinic = Clinic::factory()->create();

    $statistics = $this->service->getStatistics($clinic->id);

    expect($statistics)->toBeArray();

});