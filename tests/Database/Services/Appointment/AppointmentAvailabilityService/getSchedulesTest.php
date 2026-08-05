<?php

use App\Enums\ScheduleSlotDuration;
use App\Models\Clinic;
use App\Models\Day;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Services\Appointment\AppointmentAvailabilityService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(AppointmentAvailabilityService::class);
});

it('returns matching schedule', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $schedule->days()->attach($monday);

    $result = $this->service->getSchedules(
        '2026-08-10',
        $doctor->id,
        $clinic->id
    );

    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->slot_duration)
        ->toBe(ScheduleSlotDuration::THIRTY);
});

it('returns empty collection when no schedules exist', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $result = $this->service->getSchedules(
        '2026-08-10',
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeEmpty();
});

it('returns empty collection when clinic does not match', function () {

    $clinic = Clinic::factory()->create();
    $anotherClinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $anotherClinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
    ]);

    $schedule->days()->attach($monday);

    $result = $this->service->getSchedules(
        '2026-08-10',
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeEmpty();
});

it('returns empty collection when doctor does not match', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();
    $anotherDoctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $anotherDoctor->id,
        'is_available' => true,
    ]);

    $schedule->days()->attach($monday);

    $result = $this->service->getSchedules(
        '2026-08-10',
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeEmpty();
});

it('returns empty collection when schedule is unavailable', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => false,
    ]);

    $schedule->days()->attach($monday);

    $result = $this->service->getSchedules(
        '2026-08-10',
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeEmpty();
});

it('returns empty collection when day does not match', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $tuesday = Day::factory()->create([
        'name' => 'Tuesday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
    ]);

    $schedule->days()->attach($tuesday);

    $result = $this->service->getSchedules(
        '2026-08-10',
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeEmpty();
});

it('returns multiple schedules for the same day', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule1 = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
    ]);

    $schedule2 = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
    ]);

    $schedule1->days()->attach($monday);
    $schedule2->days()->attach($monday);

    $result = $this->service->getSchedules(
        '2026-08-10',
        $doctor->id,
        $clinic->id
    );

    expect($result)->toHaveCount(2);
});

it('returns only matching schedules among many', function () {

    $clinic = Clinic::factory()->create();
    $anotherClinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();
    $anotherDoctor = Doctor::factory()->create();

    $monday = Day::factory()->create(['name' => 'Monday']);
    $tuesday = Day::factory()->create(['name' => 'Tuesday']);
    $matching = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $matching->days()->attach($monday);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $anotherClinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
    ]);
    $schedule->days()->attach($monday);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $anotherDoctor->id,
        'is_available' => true,
    ]);
    $schedule->days()->attach($monday);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => false,
    ]);
    $schedule->days()->attach($monday);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
    ]);
    $schedule->days()->attach($tuesday);

    $result = $this->service->getSchedules(
        '2026-08-10',
        $doctor->id,
        $clinic->id
    );

    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->start_time)
        ->toBe('09:00:00')
        ->and($result->first()->slot_duration)
        ->toBe(ScheduleSlotDuration::THIRTY);
});
