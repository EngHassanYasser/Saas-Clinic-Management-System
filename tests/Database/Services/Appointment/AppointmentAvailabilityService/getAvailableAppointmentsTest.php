<?php

use App\Enums\ScheduleSlotDuration;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Day;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Services\Appointment\AppointmentAvailabilityService;

beforeEach(function () {
    $this->service = app(AppointmentAvailabilityService::class);
});
it('returns empty array for past dates', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $result = $this->service->getAvailableAppointments(
        $clinic->id,
        $doctor->id,
        now()->subDay()->toDateString()
    );

    expect($result)->toBe([]);
});
it('returns available slots for future date', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id'     => $clinic->id,
        'doctor_id'     => $doctor->id,
        'is_available'  => true,
        'start_time'    => '09:00:00',
        'end_time'      => '11:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
        'start_break'   => null,
        'end_break'     => null,
    ]);

    $schedule->days()->attach($monday);

    Appointment::factory()->create([
        'clinic_id'  => $clinic->id,
        'doctor_id'  => $doctor->id,
        'visit_date' => '2026-08-10',
        'start_time' => '09:30:00',
        'end_time'   => '10:00:00',
    ]);

    $result = $this->service->getAvailableAppointments(
        $clinic->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($result)->toBe([
        '09:00',
        '10:00',
        '10:30',
    ]);
});
it('returns empty array when no schedules exist', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $result = $this->service->getAvailableAppointments(
        $clinic->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($result)->toBe([]);
});
it('returns empty array when all slots are booked', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id'     => $clinic->id,
        'doctor_id'     => $doctor->id,
        'is_available'  => true,
        'start_time'    => '09:00:00',
        'end_time'      => '10:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
        'start_break'   => null,
        'end_break'     => null,
    ]);

    $schedule->days()->attach($monday);

    Appointment::factory()->create([
        'clinic_id'         => $clinic->id,
        'doctor_id'         => $doctor->id,
        'visit_date'        => '2026-08-10',
        'start_time'        => '09:00:00',
        'end_time'          => '09:30:00',
    ]);

    Appointment::factory()->create([
        'clinic_id'         => $clinic->id,
        'doctor_id'         => $doctor->id,
        'visit_date'        => '2026-08-10',
        'start_time'        => '09:30:00',
        'end_time'          => '10:00:00',
    ]);

    $result = $this->service->getAvailableAppointments(
        $clinic->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($result)->toBe([]);
});