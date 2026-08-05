<?php

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Services\Appointment\AppointmentAvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(AppointmentAvailabilityService::class);
});

it('returns booked slots for matching clinic doctor and date', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id'  => $clinic->id,
        'doctor_id'  => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '09:00:00',
        'end_time'   => '09:30:00',
    ]);

    Appointment::factory()->create([
        'clinic_id'  => $clinic->id,
        'doctor_id'  => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '10:00:00',
        'end_time'   => '10:30:00',
    ]);

    expect(
        $this->service->getBookedSlots(
            $clinic->id,
            $doctor->id,
            '2026-08-10'
        )
    )->toBe([
        '09:00',
        '10:00',
    ]);
});

it('returns empty array when no appointments exist', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    expect(
        $this->service->getBookedSlots(
            $clinic->id,
            $doctor->id,
            '2026-08-10'
        )
    )->toBe([]);
});

it('ignores appointments from another clinic', function () {

    $clinic = Clinic::factory()->create();
    $anotherClinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id'  => $anotherClinic->id,
        'doctor_id'  => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '09:00:00',
        'end_time'   => '09:30:00',
    ]);

    expect(
        $this->service->getBookedSlots(
            $clinic->id,
            $doctor->id,
            '2026-08-10'
        )
    )->toBe([]);
});

it('ignores appointments from another doctor', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();
    $anotherDoctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id'  => $clinic->id,
        'doctor_id'  => $anotherDoctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '09:00:00',
        'end_time'   => '09:30:00',
    ]);

    expect(
        $this->service->getBookedSlots(
            $clinic->id,
            $doctor->id,
            '2026-08-10'
        )
    )->toBe([]);
});

it('ignores appointments from another visit date', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id'  => $clinic->id,
        'doctor_id'  => $doctor->id,
        'visit_date' => '2026-08-11',
        'start_time' => '09:00:00',
        'end_time'   => '09:30:00',
    ]);

    expect(
        $this->service->getBookedSlots(
            $clinic->id,
            $doctor->id,
            '2026-08-10'
        )
    )->toBe([]);
});

it('returns only matching appointments among many', function () {

    $clinic = Clinic::factory()->create();
    $anotherClinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();
    $anotherDoctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id'  => $clinic->id,
        'doctor_id'  => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '09:00:00',
        'end_time'   => '09:30:00',
    ]);

    Appointment::factory()->create([
        'clinic_id'  => $anotherClinic->id,
        'doctor_id'  => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '10:00:00',
        'end_time'   => '10:30:00',
    ]);

    Appointment::factory()->create([
        'clinic_id'  => $clinic->id,
        'doctor_id'  => $anotherDoctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '11:00:00',
        'end_time'   => '11:30:00',
    ]);

    Appointment::factory()->create([
        'clinic_id'  => $clinic->id,
        'doctor_id'  => $doctor->id,
        'visit_date' => '2026-08-11',
        'start_time' => '12:00:00',
        'end_time'   => '12:30:00',
    ]);

    expect(
        $this->service->getBookedSlots(
            $clinic->id,
            $doctor->id,
            '2026-08-10'
        )
    )->toBe([
        '09:00',
    ]);
});

it('returns slots formatted as hours and minutes', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    Appointment::factory()->create([
        'clinic_id'  => $clinic->id,
        'doctor_id'  => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '14:45:00',
        'end_time'   => '15:15:00',
    ]);

    expect(
        $this->service->getBookedSlots(
            $clinic->id,
            $doctor->id,
            '2026-08-10'
        )
    )->toBe([
        '14:45',
    ]);
});