<?php

use App\Models\ClinicService;
use App\Models\Speciality;
use App\Services\Clinic\ClinicQueryService;
use Illuminate\Database\Eloquent\Collection;

it('returns clinic services for the given speciality', function () {

    $speciality = Speciality::factory()->create();

    ClinicService::factory()->count(3)->create([
        'speciality_id' => $speciality->id,
    ]);

    ClinicService::factory()->count(2)->create();

    $result = app(ClinicQueryService::class)
        ->getClinicServicesBySpecialityId($speciality->id);

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3);
});

it('returns only id and name columns', function () {

    $speciality = Speciality::factory()->create();

    ClinicService::factory()->create([
        'speciality_id' => $speciality->id,
    ]);

    $service = app(ClinicQueryService::class)
        ->getClinicServicesBySpecialityId($speciality->id)
        ->first();

    expect(array_keys($service->getAttributes()))
        ->toBe([
            'id',
            'name',
        ]);
});

it('returns empty collection when speciality has no services', function () {

    $speciality = Speciality::factory()->create();

    $result = app(ClinicQueryService::class)
        ->getClinicServicesBySpecialityId($speciality->id);

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it('does not return services from other specialities', function () {

    $requested = Speciality::factory()->create();

    $other = Speciality::factory()->create();

    ClinicService::factory()->count(2)->create([
        'speciality_id' => $requested->id,
    ]);

    ClinicService::factory()->count(5)->create([
        'speciality_id' => $other->id,
    ]);

    $result = app(ClinicQueryService::class)
        ->getClinicServicesBySpecialityId($requested->id);

    expect($result)
        ->toHaveCount(2);

    expect(
        $result->pluck('id')->count()
    )->toBe(2);

    expect(
        $result->pluck('name')->count()
    )->toBe(2);
});