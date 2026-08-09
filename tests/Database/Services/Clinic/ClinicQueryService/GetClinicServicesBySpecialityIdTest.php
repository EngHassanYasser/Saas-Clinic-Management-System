<?php

use App\Models\DoctorService;
use App\Models\Speciality;
use App\Services\Clinic\ClinicQueryService;
use Illuminate\Database\Eloquent\Collection;

it('returns clinic services for the given speciality', function () {

    $speciality = Speciality::factory()->create();

    DoctorService::factory()->count(3)->create([
        'speciality_id' => $speciality->id,
    ]);

    DoctorService::factory()->count(2)->create();

    $result = app(ClinicQueryService::class)
        ->getDoctorServicesBySpecialityId($speciality->id);

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(3);
});

it('returns only id and name columns', function () {

    $speciality = Speciality::factory()->create();

    DoctorService::factory()->create([
        'speciality_id' => $speciality->id,
    ]);

    $service = app(ClinicQueryService::class)
        ->getDoctorServicesBySpecialityId($speciality->id)
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
        ->getDoctorServicesBySpecialityId($speciality->id);

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

it('does not return services from other specialities', function () {

    $requested = Speciality::factory()->create();

    $other = Speciality::factory()->create();

    DoctorService::factory()->count(2)->create([
        'speciality_id' => $requested->id,
    ]);

    DoctorService::factory()->count(5)->create([
        'speciality_id' => $other->id,
    ]);

    $result = app(ClinicQueryService::class)
        ->getDoctorServicesBySpecialityId($requested->id);

    expect($result)
        ->toHaveCount(2);

    expect(
        $result->pluck('id')->count()
    )->toBe(2);

    expect(
        $result->pluck('name')->count()
    )->toBe(2);
});