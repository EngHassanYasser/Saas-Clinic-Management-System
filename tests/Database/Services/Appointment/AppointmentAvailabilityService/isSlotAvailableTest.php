<?php

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Services\Appointment\AppointmentAvailabilityService;

beforeEach(function () {
    $this->service = app(AppointmentAvailabilityService::class);
});
it('returns true when no appointment exists', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $available = $this->service->isSlotAvailable(
        $clinic->id,
        $doctor->id,
        '2026-08-10',
        '10:00:00'
    );

    expect($available)->toBeTrue();
});
it('returns false when slot is already booked', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '10:00:00',
        'end_time' => '10:30:00',
    ]);

    $available = $this->service->isSlotAvailable(
        $clinic->id,
        $doctor->id,
        '2026-08-10',
        '10:00:00'
    );

    expect($available)->toBeFalse();
});
it('returns true when appointment belongs to another doctor', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();
    $anotherDoctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $anotherDoctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '10:00:00',
        'end_time' => '10:30:00',
    ]);

    expect(
        $this->service->isSlotAvailable(
            $clinic->id,
            $doctor->id,
            '2026-08-10',
            '10:00:00'
        )
    )->toBeTrue();
});
it('returns true when appointment belongs to another clinic', function () {

    $clinic = Clinic::factory()->create();
    $anotherClinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id' => $anotherClinic->id,
        'doctor_id' => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '10:00:00',
        'end_time' => '10:30:00',
    ]);

    expect(
        $this->service->isSlotAvailable(
            $clinic->id,
            $doctor->id,
            '2026-08-10',
            '10:00:00'
        )
    )->toBeTrue();
});
it('returns true when appointment is on another date', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'visit_date' => '2026-08-11',
        'start_time' => '10:00:00',
        'end_time' => '10:30:00',
    ]);

    expect(
        $this->service->isSlotAvailable(
            $clinic->id,
            $doctor->id,
            '2026-08-10',
            '10:00:00'
        )
    )->toBeTrue();
});
it('returns true when appointment is at another time', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '11:00:00',
        'end_time' => '11:30:00',
    ]);

    expect(
        $this->service->isSlotAvailable(
            $clinic->id,
            $doctor->id,
            '2026-08-10',
            '10:00:00'
        )
    )->toBeTrue();
});
it('returns false when one matching appointment exists among many', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    Appointment::factory()->count(5)->create();

    Appointment::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '10:00:00',
        'end_time' => '10:30:00',
    ]);

    expect(
        $this->service->isSlotAvailable(
            $clinic->id,
            $doctor->id,
            '2026-08-10',
            '10:00:00'
        )
    )->toBeFalse();
});
