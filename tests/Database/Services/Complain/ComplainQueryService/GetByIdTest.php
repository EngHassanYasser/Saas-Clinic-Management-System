<?php

use App\Enums\ComplainStatus;
use App\Models\Complain;
use App\Services\Complain\ComplainQueryService;

beforeEach(function () {
    $this->service = app(ComplainQueryService::class);
});

it('returns complain by id', function () {

    $complain = Complain::factory()->create();

    $result = $this->service->getById($complain->id);

    expect($result)
        ->toBeInstanceOf(Complain::class)
        ->and($result->id)
        ->toBe($complain->id);

});
it('returns correct complain data', function () {

    $complain = Complain::factory()->create([
        'description' => 'Bad reception service',
        'status' => ComplainStatus::PENDING,
        'severity' => 'high',
    ]);

    $result = $this->service->getById($complain->id);

    expect($result->description)
        ->toBe('Bad reception service')
        ->and($result->status)
        ->toBe(ComplainStatus::PENDING)
        ->and($result->severity)
        ->toBe('high');

});
it('does not return another complain', function () {

    $first = Complain::factory()->create();

    $second = Complain::factory()->create();

    $result = $this->service->getById($first->id);

    expect($result->id)
        ->not->toBe($second->id);

});
it('returns complain model not array', function () {

    $complain = Complain::factory()->create();

    $result = $this->service->getById($complain->id);

    expect($result)
        ->toBeInstanceOf(Complain::class);

});
