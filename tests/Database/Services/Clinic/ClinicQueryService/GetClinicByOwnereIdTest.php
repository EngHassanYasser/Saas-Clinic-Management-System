<?php

use App\Models\City;
use App\Models\Clinic;
use App\Models\Day;
use App\Models\User;
use App\Services\Clinic\ClinicQueryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

it('returns clinic by owner id', function () {

    $owner = User::factory()->clinic()->create();

    $city = City::factory()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
        'city_id' => $city->id,
    ]);

    $result = app(ClinicQueryService::class)
        ->getClinicByOwnereId($owner->id);

    expect($result->id)->toBe($clinic->id);

    expect($result->owner_id)->toBe($owner->id);
});

it('loads city and days relationships', function () {

    $owner = User::factory()->clinic()->create();

    $city = City::factory()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
        'city_id' => $city->id,
    ]);

    $days = Day::factory()->count(3)->create();

    $clinic->days()->attach($days->pluck('id'));

    $result = app(ClinicQueryService::class)
        ->getClinicByOwnereId($owner->id);

    expect($result->relationLoaded('city'))->toBeTrue();

    expect($result->relationLoaded('days'))->toBeTrue();

    expect($result->days)->toHaveCount(3);
});

it('returns logo url', function () {

    Storage::fake('public');

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $clinic
        ->addMediaFromString('logo')
        ->usingFileName('logo.png')
        ->toMediaCollection('logo');

    $result = app(ClinicQueryService::class)
        ->getClinicByOwnereId($owner->id);

    expect($result->logo)->not->toBe('');
});

it('throws exception when owner has no clinic', function () {

    $owner = User::factory()->clinic()->create();

    app(ClinicQueryService::class)
        ->getClinicByOwnereId($owner->id);

})->throws(ModelNotFoundException::class);

it('returns the correct clinic when multiple clinics exist', function () {

    Clinic::factory()->count(3)->create();

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $result = app(ClinicQueryService::class)
        ->getClinicByOwnereId($owner->id);

    expect($result->id)->toBe($clinic->id);
});