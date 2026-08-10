<?php

use App\Enums\ComplaintStatus;
use App\Models\Complaint;
use App\Services\Complaint\ComplaintQueryService;

beforeEach(function () {
    $this->service = app(ComplaintQueryService::class);
});

it('returns complaint by id', function () {

    $complaint = Complaint::factory()->create();

    $result = $this->service->getById($complaint->id);

    expect($result)
        ->toBeInstanceOf(Complaint::class)
        ->and($result->id)
        ->toBe($complaint->id);

});
it('returns correct complaint data', function () {

    $complaint = Complaint::factory()->create([
        'description' => 'Bad reception service',
        'status' => ComplaintStatus::PENDING,
        'severity' => 'high',
    ]);

    $result = $this->service->getById($complaint->id);

    expect($result->description)
        ->toBe('Bad reception service')
        ->and($result->status)
        ->toBe(ComplaintStatus::PENDING)
        ->and($result->severity)
        ->toBe('high');

});
it('does not return another complaint', function () {

    $first = Complaint::factory()->create();

    $second = Complaint::factory()->create();

    $result = $this->service->getById($first->id);

    expect($result->id)
        ->not->toBe($second->id);

});
it('returns complaint model not array', function () {

    $complaint = Complaint::factory()->create();

    $result = $this->service->getById($complaint->id);

    expect($result)
        ->toBeInstanceOf(Complaint::class);

});
