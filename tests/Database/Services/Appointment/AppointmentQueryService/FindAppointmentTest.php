<?php

use App\Models\User;
use App\Models\Clinic;
use App\Models\Appointment;
use App\Enums\RoleType;
use App\Services\Appointment\AppointmentQueryService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {
    $this->service = app(AppointmentQueryService::class);
});

it('returns appointment for clinic owner when appointment belongs to his clinic', function () {

    $clinicOwner = User::factory()->create([
        'type' => RoleType::CLINIC,
    ]);

    $clinic = Clinic::factory()->create([
        'owner_id' => $clinicOwner->id,
    ]);

    $appointment = Appointment::factory()->create([
        'clinic_id' => $clinic->id,
    ]);


    $service = app(AppointmentQueryService::class);

    $result = $service->findAppointment(
        $appointment->id,
        $clinicOwner
    );


    expect($result->id)
        ->toBe($appointment->id);
});


it('does not return appointment from another clinic for clinic owner', function () {

    $clinicOwner = User::factory()->create([
        'type' => RoleType::CLINIC,
    ]);

    $clinic = Clinic::factory()->create([
        'owner_id' => $clinicOwner->id,
    ]);


    $otherClinic = Clinic::factory()->create();


    $appointment = Appointment::factory()->create([
        'clinic_id' => $otherClinic->id,
    ]);


    $service = app(AppointmentQueryService::class);


    expect(fn() =>
        $service->findAppointment(
            $appointment->id,
            $clinicOwner
        )
    )
    ->toThrow(ModelNotFoundException::class);
});



it('returns appointment for patient when appointment belongs to patient', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);


    $appointment = Appointment::factory()->create([
        'patient_id' => $patient->id,
    ]);


    $service = app(AppointmentQueryService::class);


    $result = $service->findAppointment(
        $appointment->id,
        $patient
    );


    expect($result->patient_id)
        ->toBe($patient->id);
});



it('does not return appointment for another patient', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);

    $otherPatient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);


    $appointment = Appointment::factory()->create([
        'patient_id' => $otherPatient->id,
    ]);


    $service = app(AppointmentQueryService::class);


    expect(fn() =>
        $service->findAppointment(
            $appointment->id,
            $patient
        )
    )
    ->toThrow(ModelNotFoundException::class);
});



it('throws exception when appointment does not exist', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);


    $service = app(AppointmentQueryService::class);


    expect(fn() =>
        $service->findAppointment(
            999999,
            $patient
        )
    )
    ->toThrow(ModelNotFoundException::class);
});



it('does not return appointment for clinic owner without clinic', function () {

    $clinicOwner = User::factory()->create([
        'type' => RoleType::CLINIC,
    ]);


    $appointment = Appointment::factory()->create();


    $service = app(AppointmentQueryService::class);


    expect(fn() =>
        $service->findAppointment(
            $appointment->id,
            $clinicOwner
        )
    )
    ->toThrow(ModelNotFoundException::class);
});