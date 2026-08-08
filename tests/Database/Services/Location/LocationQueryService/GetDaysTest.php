<?php

use App\Models\Day;
use App\Services\Location\LocationQueryService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->service = app(LocationQueryService::class);
});


it('returns all week days', function () {

    Day::factory()
        ->count(7)
        ->create();


    $result = $this->service->getDays();


    expect($result)->toHaveCount(7);
});



it('returns day as eloquent collection', function () {

    Day::factory()->create();


    $result = $this->service->getDays();


    expect($result)
        ->toBeInstanceOf(Collection::class);
});



it('returns only id and name columns', function () {

    $day = Day::factory()->create([
        'name' => 'Saturday',
    ]);


    $result = $this->service->getDays()
        ->firstWhere('id', $day->id);



    expect($result->name)
        ->toBe('Saturday');


    expect($result->getAttributes())
        ->not->toHaveKey('created_at')
        ->not->toHaveKey('updated_at');
});



it('returns correct day names', function () {

    Day::factory()->create([
        'name' => 'Saturday',
    ]);


    Day::factory()->create([
        'name' => 'Sunday',
    ]);



    $result = $this->service->getDays();



    expect($result->pluck('name')->toArray())
        ->toContain('Saturday', 'Sunday');
});



it('returns empty collection when there are no days', function () {

    $result = $this->service->getDays();


    expect($result)
        ->toBeEmpty();
});



it('does not return unrelated columns from days table', function () {

    $day = Day::factory()->create();


    $result = $this->service->getDays()
        ->firstWhere('id', $day->id);



    expect(array_keys($result->getAttributes()))
        ->toEqualCanonicalizing([
            'id',
            'name',
        ]);
});



it('returns all created days without missing records', function () {

    $days = Day::factory()
        ->count(7)
        ->create();


    $result = $this->service->getDays();



    expect($result->pluck('id')->toArray())
        ->toEqualCanonicalizing(
            $days->pluck('id')->toArray()
        );
});



it('does not duplicate days', function () {

    Day::factory()->create([
        'name' => 'Monday',
    ]);

    $result = $this->service->getDays();

    expect($result)
        ->toHaveCount(1);
});