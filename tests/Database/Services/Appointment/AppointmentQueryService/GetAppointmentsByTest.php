<?php

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\ClinicService;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use App\Models\User;
use App\Services\Appointment\AppointmentQueryService;
use Illuminate\Pagination\LengthAwarePaginator;

beforeEach(function () {
    $this->service = app(AppointmentQueryService::class);
});

it('returns appointments filtered by column with pagination', function () {

    $patient = User::factory()->create();

    $appointment = Appointment::factory()->create([
        'patient_id' => $patient->id,
    ]);

    Appointment::factory()->count(3)->create();

    $result = $this->service->getAppointmentsBy(
        'patient_id',
        $patient->id
    );

    expect($result)
        ->toBeInstanceOf(LengthAwarePaginator::class);

    expect($result->total())
        ->toBe(1);

    expect($result->first()['id'])
        ->toBe($appointment->id);
});

it('loads appointment relations correctly', function () {

    $patient = User::factory()->create([
        'name' => 'Ahmed',
    ]);

    $doctor = Doctor::factory()->create([
        'name' => 'Doctor Test',
    ]);

    $clinic = Clinic::factory()->create([
        'name' => 'Clinic Test',
        'address' => 'Cairo',
    ]);

    $service = ClinicService::factory()->create([
        'name' => 'Checkup',
    ]);

    $appointment = Appointment::factory()->create([
        'patient_id' => $patient->id,
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
        'clinic_service_id' => $service->id,
    ]);

    $result = $this->service->getAppointmentsBy(
        'id',
        $appointment->id
    )
        ->first();

    expect($result['patient']['name'])
        ->toBe('Ahmed');

    expect($result['doctor']['name'])
        ->toBe('Doctor Test');

    expect($result['clinic']['name'])
        ->toBe('Clinic Test');

    expect($result['service']['name'])
        ->toBe('Checkup');
});

it('returns correct service price based on doctor clinic and service', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $service = ClinicService::factory()->create();

    Doctor_service_price::create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
        'clinic_service_id' => $service->id,
        'description' => 'this is mock description',
        'price' => 500,
    ]);

    $appointment = Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
        'clinic_service_id' => $service->id,
    ]);

    $result = $this->service->getAppointmentsBy(
        'id',
        $appointment->id
    )->first();

    expect($result['service']['price'])
        ->toBe('500.00');
});

it('does not return wrong service price', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $service = ClinicService::factory()->create();

    Doctor_service_price::create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
        'clinic_service_id' => $service->id,
        'description' => 'this is mock description',
        'price' => 500,
    ]);

    $otherService = ClinicService::factory()->create();

    Appointment::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
        'clinic_service_id' => $otherService->id,
    ]);

    $result = $this->service->getAppointmentsBy(
        'clinic_id',
        $clinic->id
    )
        ->first();

    expect($result['service']['price'])
        ->toBeNull();
});

it('paginates appointments only for requested patient', function () {

    $patient = User::factory()->create();

    $otherPatient = User::factory()->create();

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $service = ClinicService::factory()->create();

    Appointment::factory()
        ->count(25)
        ->create([
            'patient_id' => $patient->id,
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'clinic_service_id' => $service->id,
        ]);

    Appointment::factory()
        ->count(10)
        ->create([
            'patient_id' => $otherPatient->id,
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'clinic_service_id' => $service->id,
        ]);

    $result = $this->service->getAppointmentsBy(
        'patient_id',
        $patient->id
    );

    expect($result->total())
        ->toBe(25);

    expect($result->count())
        ->toBe(20);
});
