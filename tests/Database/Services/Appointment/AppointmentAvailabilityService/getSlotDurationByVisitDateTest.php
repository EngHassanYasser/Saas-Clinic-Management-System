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

it('returns slot duration when schedule matches', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id'     => $clinic->id,
        'doctor_id'     => $doctor->id,
        'is_available'  => true,
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $schedule->days()->attach($monday);

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($duration)->toBe(30);
});

it('returns zero when doctor has no schedule', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($duration)->toBe(0);
});

it('returns zero when clinic does not match', function () {

    $clinic1 = Clinic::factory()->create();

    $clinic2 = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id'     => $clinic1->id,
        'doctor_id'     => $doctor->id,
        'is_available'  => true,
        'slot_duration' => ScheduleSlotDuration::FIFTEEN,
    ]);

    $schedule->days()->attach($monday);

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic2->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($duration)->toBe(0);
});

it('returns zero when doctor does not match', function () {

    $clinic = Clinic::factory()->create();

    $doctor1 = Doctor::factory()->create();

    $doctor2 = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id'     => $clinic->id,
        'doctor_id'     => $doctor1->id,
        'is_available'  => true,
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $schedule->days()->attach($monday);

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor2->id,
        '2026-08-10'
    );

    expect($duration)->toBe(0);
});

it('returns zero when schedule is unavailable', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create([
        'name' => 'Monday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id'     => $clinic->id,
        'doctor_id'     => $doctor->id,
        'is_available'  => false,
        'slot_duration' => ScheduleSlotDuration::FIFTEEN,
    ]);

    $schedule->days()->attach($monday);

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($duration)->toBe(0);
});

it('returns zero when visit day is not assigned to schedule', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $tuesday = Day::factory()->create([
        'name' => 'Tuesday',
    ]);

    $schedule = Schedule::factory()->create([
        'clinic_id'     => $clinic->id,
        'doctor_id'     => $doctor->id,
        'is_available'  => true,
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $schedule->days()->attach($tuesday);

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor->id,
        '2026-08-10' // Monday
    );

    expect($duration)->toBe(0);
});
it('returns slot duration when schedule has multiple assigned days', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create(['name' => 'Monday']);
    $tuesday = Day::factory()->create(['name' => 'Tuesday']);
    $wednesday = Day::factory()->create(['name' => 'Wednesday']);

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
        'slot_duration' => ScheduleSlotDuration::FORTY_FIVE,
    ]);

    $schedule->days()->attach([
        $monday->id,
        $tuesday->id,
        $wednesday->id,
    ]);

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor->id,
        '2026-08-11' // Tuesday
    );

    expect($duration)->toBe(45);
});
it('ignores schedules from other clinics', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $monday = Day::factory()->create(['name' => 'Monday']);

    Schedule::factory()->create([
        'clinic_id' => $clinic2->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
        'slot_duration' => ScheduleSlotDuration::SIXTY,
    ])->days()->attach($monday);

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic1->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($duration)->toBe(0);
});
it('ignores schedules from other doctors', function () {

    $clinic = Clinic::factory()->create();

    $doctor1 = Doctor::factory()->create();
    $doctor2 = Doctor::factory()->create();

    $monday = Day::factory()->create(['name' => 'Monday']);

    Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor2->id,
        'is_available' => true,
        'slot_duration' => ScheduleSlotDuration::FORTY_FIVE,
    ])->days()->attach($monday);

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor1->id,
        '2026-08-10'
    );

    expect($duration)->toBe(0);
});
it('returns integer slot duration', function () {

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

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($duration)
        ->toBeInt()
        ->toBe(30);
});
it('returns zero when no schedule matches', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($duration)->toBe(0);
});
it('returns zero when schedule has no assigned days', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'is_available' => true,
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $duration = $this->service->getSlotDurationByVisitDate(
        $clinic->id,
        $doctor->id,
        '2026-08-10'
    );

    expect($duration)->toBe(0);
});