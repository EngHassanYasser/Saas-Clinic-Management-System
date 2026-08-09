<?php

use App\Models\Clinic;
use App\Models\DoctorService;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use App\Models\Speciality;
use App\Services\Doctor\DoctorQueryService;
use Illuminate\Support\Collection;

beforeEach(function () {
    $this->service = app(DoctorQueryService::class);
});

it('returns only doctors who match the requested speciality and service', function () {
    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();
    $otherSpeciality = Speciality::factory()->create();

    $service = DoctorService::factory()->create();
    $otherService = DoctorService::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->specialities()->attach($speciality->id);

    Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'doctorService_id' => $service->id,
    ]);

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $speciality->id,
        $service->id
    );

    expect($result)->toHaveCount(1)
        ->and($result->first()->id)->toBe($doctor->id)
        ->and($result->first()->name)->toBe($doctor->name);
});

it('does not return doctors with a different speciality', function () {
    $clinic = Clinic::factory()->create();

    $requestedSpeciality = Speciality::factory()->create();
    $otherSpeciality = Speciality::factory()->create();

    $service = DoctorService::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->specialities()->attach($otherSpeciality->id);

    Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'doctorService_id' => $service->id,
    ]);

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $requestedSpeciality->id,
        $service->id
    );

    expect($result)->toBeEmpty();
});

it('does not return doctors with a different service', function () {
    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();

    $requestedService = DoctorService::factory()->create();
    $otherService = DoctorService::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->specialities()->attach($speciality->id);

    Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'doctorService_id' => $otherService->id,
    ]);

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $speciality->id,
        $requestedService->id
    );

    expect($result)->toBeEmpty();
});

it('does not return doctors whose service belongs to another clinic', function () {
    $clinic = Clinic::factory()->create();
    $otherClinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();
    $service = DoctorService::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->specialities()->attach($speciality->id);

    Doctor_service_price::factory()->create([
        'clinic_id' => $otherClinic->id,
        'doctor_id' => $doctor->id,
        'doctorService_id' => $service->id,
    ]);

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $speciality->id,
        $service->id
    );

    expect($result)->toBeEmpty();
});

it('does not return doctors who are not assigned to the requested speciality', function () {
    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();
    $otherSpeciality = Speciality::factory()->create();

    $service = DoctorService::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->specialities()->attach($otherSpeciality->id);

    Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'doctorService_id' => $service->id,
    ]);

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $speciality->id,
        $service->id
    );

    expect($result)->toBeEmpty();
});

it('returns multiple matching doctors', function () {
    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();
    $service = DoctorService::factory()->create();

    $doctors = Doctor::factory()->count(3)->create();

    foreach ($doctors as $doctor) {
        $doctor->specialities()->attach($speciality->id);

        Doctor_service_price::factory()->create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'doctorService_id' => $service->id,
        ]);
    }

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $speciality->id,
        $service->id
    );

    expect($result)->toHaveCount(3)
        ->and($result->pluck('id')->sort()->values()->all())
        ->toBe($doctors->pluck('id')->sort()->values()->all());
});

it('does not return duplicate doctors when a doctor has multiple specialities', function () {
    $clinic = Clinic::factory()->create();

    $requestedSpeciality = Speciality::factory()->create();
    $otherSpeciality = Speciality::factory()->create();

    $service = DoctorService::factory()->create();

    $doctor = Doctor::factory()->create();

    /*
     * The doctor has multiple specialities.
     * The requested speciality exists among them.
     */
    $doctor->specialities()->attach([
        $requestedSpeciality->id,
        $otherSpeciality->id,
    ]);

    /*
     * Only one doctor_service_price is created because
     * (clinic_id, doctor_id, doctorService_id) is unique.
     */
    Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'doctorService_id' => $service->id,
    ]);

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $requestedSpeciality->id,
        $service->id
    );

    /*
     * distinct() guarantees that the doctor appears once.
     */
    expect($result)->toHaveCount(1)
        ->and($result->pluck('id')->all())
        ->toBe([$doctor->id]);
});

it('returns an empty collection when no doctors match', function () {
    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();
    $service = DoctorService::factory()->create();

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $speciality->id,
        $service->id
    );

    expect($result)->toBeInstanceOf(Collection::class)
        ->and($result)->toBeEmpty();
});

it('returns only id and name for matching doctors', function () {
    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();
    $service = DoctorService::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->specialities()->attach($speciality->id);

    Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'doctorService_id' => $service->id,
    ]);

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $speciality->id,
        $service->id
    );

    expect($result->first()->getAttributes())
        ->toHaveKeys([
            'id',
            'name',
        ])
        ->toHaveCount(2);
});

it('does not return a doctor when the speciality matches but the service is missing', function () {
    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();
    $service = DoctorService::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->specialities()->attach($speciality->id);

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $speciality->id,
        $service->id
    );

    expect($result)->toBeEmpty();
});

it('does not return a doctor when the service matches but the speciality is missing', function () {
    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();
    $service = DoctorService::factory()->create();

    $doctor = Doctor::factory()->create();

    Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'doctorService_id' => $service->id,
    ]);

    $result = $this->service->getAvailableDoctors(
        $clinic->id,
        $speciality->id,
        $service->id
    );

    expect($result)->toBeEmpty();
});