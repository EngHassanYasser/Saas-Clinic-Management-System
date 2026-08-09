<?php

use App\Models\City;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\DoctorService;
use App\Services\Clinic\ClinicQueryService;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

it('returns available clinics', function () {

    $city = City::factory()->create([
        'name' => 'Cairo',
    ]);

    $clinic = Clinic::factory()->create([
        'city_id' => $city->id,
    ]);

    $doctor = Doctor::factory()->create();

    $speciality = Speciality::factory()->create();

    $service = DoctorService::factory()->create([
        'speciality_id' => $speciality->id,
    ]);

    DB::table('clinic_doctors')->insert([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_active' => true,
    ]);

    DB::table('doctor_speciality')->insert([
        'doctor_id' => $doctor->id,
        'speciality_id' => $speciality->id,
    ]);

    $result = app(ClinicQueryService::class)
        ->getAvailableClinics($speciality->id, $service->id);

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toHaveCount(1);

    expect($result->first()->id)
        ->toBe($clinic->id);

    expect($result->first()->name)
        ->toBe($clinic->name);

    expect($result->first()->city_name)
        ->toBe('Cairo');
});

it('does not return inactive doctors', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $speciality = Speciality::factory()->create();

    $service = DoctorService::factory()->create([
        'speciality_id' => $speciality->id,
    ]);

    DB::table('clinic_doctors')->insert([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_active' => false,
    ]);

    DB::table('doctor_speciality')->insert([
        'doctor_id' => $doctor->id,
        'speciality_id' => $speciality->id,
    ]);

    $result = app(ClinicQueryService::class)
        ->getAvailableClinics($speciality->id, $service->id);

    expect($result)->toBeEmpty();
});

it('does not return clinic for wrong speciality', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctorSpeciality = Speciality::factory()->create();

    $requestedSpeciality = Speciality::factory()->create();

    $service = DoctorService::factory()->create([
        'speciality_id' => $requestedSpeciality->id,
    ]);

    DB::table('clinic_doctors')->insert([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_active' => true,
    ]);

    DB::table('doctor_speciality')->insert([
        'doctor_id' => $doctor->id,
        'speciality_id' => $doctorSpeciality->id,
    ]);

    $result = app(ClinicQueryService::class)
        ->getAvailableClinics($requestedSpeciality->id, $service->id);

    expect($result)->toBeEmpty();
});

it('does not return clinic for wrong service', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $speciality = Speciality::factory()->create();

    $service = DoctorService::factory()->create([
        'speciality_id' => $speciality->id,
    ]);

    DB::table('clinic_doctors')->insert([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_active' => true,
    ]);

    DB::table('doctor_speciality')->insert([
        'doctor_id' => $doctor->id,
        'speciality_id' => $speciality->id,
    ]);

    $result = app(ClinicQueryService::class)
        ->getAvailableClinics(
            $speciality->id,
            999999
        );

    expect($result)->toBeEmpty();
});

it('returns clinic only once using distinct', function () {

    $clinic = Clinic::factory()->create();

    $doctor1 = Doctor::factory()->create();

    $doctor2 = Doctor::factory()->create();

    $speciality = Speciality::factory()->create();

    $service = DoctorService::factory()->create([
        'speciality_id' => $speciality->id,
    ]);

    DB::table('clinic_doctors')->insert([
        [
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor1->id,
            'is_active' => true,
        ],
        [
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor2->id,
            'is_active' => true,
        ],
    ]);

    DB::table('doctor_speciality')->insert([
        [
            'doctor_id' => $doctor1->id,
            'speciality_id' => $speciality->id,
        ],
        [
            'doctor_id' => $doctor2->id,
            'speciality_id' => $speciality->id,
        ],
    ]);

    $result = app(ClinicQueryService::class)
        ->getAvailableClinics($speciality->id, $service->id);

    expect($result)
        ->toHaveCount(1);
});