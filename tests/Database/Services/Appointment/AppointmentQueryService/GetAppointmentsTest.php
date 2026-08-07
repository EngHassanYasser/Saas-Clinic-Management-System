<?php

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use App\Services\Appointment\AppointmentQueryService;
use Illuminate\Pagination\LengthAwarePaginator;
beforeEach(function () {
    $this->service = app(AppointmentQueryService::class);
});
it('returns only patient appointments', function () {

    $patient = User::factory()->patient()->create();

    Appointment::factory()->count(3)->create([
        'patient_id' => $patient->id,
    ]);

    Appointment::factory()->count(5)->create();

    $result = $this->service->getAppointments($patient);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);

    expect($result->total())->toBe(3);
});

it('returns only clinic appointments', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    Appointment::factory()->count(4)->create([
        'clinic_id' => $clinic->id,
    ]);

    Appointment::factory()->count(6)->create();

    $result = $this->service->getAppointments($owner);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);

    expect($result->total())->toBe(4);
});

it('returns empty paginator when patient has no appointments', function () {

    $patient = User::factory()->patient()->create();

    $result = $this->service->getAppointments($patient);

    expect($result->total())->toBe(0);
});

it('returns empty paginator when clinic has no appointments', function () {

    $owner = User::factory()->clinic()->create();

    Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $result = $this->service->getAppointments($owner);

    expect($result->total())->toBe(0);
});

it('returns empty paginator for unsupported role', function () {

    $user = User::factory()->create();

    $result = $this->service->getAppointments($user);

    expect($result)->toBeInstanceOf(LengthAwarePaginator::class);

    expect($result->total())->toBe(0);
});

it('paginates patient appointments', function () {

    $patient = User::factory()->patient()->create();

    Appointment::factory()->count(25)->create([
        'patient_id' => $patient->id,
    ]);

    $result = $this->service->getAppointments($patient);

    expect($result->perPage())->toBe(20);

    expect($result->count())->toBe(20);

    expect($result->total())->toBe(25);
});

it('does not return appointments for other patients', function () {

    $patient = User::factory()->patient()->create();

    Appointment::factory()->count(2)->create([
        'patient_id' => $patient->id,
    ]);

    Appointment::factory()->count(8)->create();

    $result = $this->service->getAppointments($patient);

    expect(
        collect($result->items())
            ->every(fn ($appt) => $appt['patient']['id'] === $patient->id)
    )->toBeTrue();
});

it('does not return appointments for other clinics', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    Appointment::factory()->count(3)->create([
        'clinic_id' => $clinic->id,
    ]);

    $otherClinic = Clinic::factory()->create();

    Appointment::factory()->count(5)->create([
        'clinic_id' => $otherClinic->id,
    ]);

    $result = $this->service->getAppointments($owner);

    expect(
        collect($result->items())
            ->every(fn ($appt) => $appt['clinic']['id'] === $clinic->id)
    )->toBeTrue();
});