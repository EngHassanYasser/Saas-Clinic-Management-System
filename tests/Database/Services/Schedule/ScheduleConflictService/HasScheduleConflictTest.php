<?php

use App\Models\Clinic;
use App\Models\Day;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Services\Schedule\ScheduleConflictService;

beforeEach(function () {
    $this->service = app(ScheduleConflictService::class);
});


it('returns true when same doctor clinic day and overlapping time exists', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
    ]);

    $schedule->days()->attach($day->id);


    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor->id,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'day_ids' => [$day->id],
        ], $clinic->id)
    )->toBeTrue();
});



it('returns false when doctor is different', function () {

    $clinic = Clinic::factory()->create();

    $doctor1 = Doctor::factory()->create();
    $doctor2 = Doctor::factory()->create();

    $day = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor1->id,
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
    ]);

    $schedule->days()->attach($day->id);



    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor2->id,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'day_ids' => [$day->id],
        ], $clinic->id)
    )->toBeFalse();
});



it('returns false when clinic is different', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic1->id,
        'doctor_id' => $doctor->id,
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
    ]);

    $schedule->days()->attach($day->id);



    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor->id,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'day_ids' => [$day->id],
        ], $clinic2->id)
    )->toBeFalse();
});



it('returns false when days are different', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $oldDay = Day::factory()->create();
    $newDay = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
    ]);

    $schedule->days()->attach($oldDay->id);



    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor->id,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'day_ids' => [$newDay->id],
        ], $clinic->id)
    )->toBeFalse();
});



it('returns false when time ranges do not overlap', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
    ]);

    $schedule->days()->attach($day->id);



    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor->id,
            'start_time' => '12:00:00',
            'end_time' => '14:00:00',
            'day_ids' => [$day->id],
        ], $clinic->id)
    )->toBeFalse();
});



it('detects conflict when new schedule starts inside existing schedule', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
    ]);

    $schedule->days()->attach($day);



    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor->id,
            'start_time' => '11:00:00',
            'end_time' => '14:00:00',
            'day_ids' => [$day->id],
        ], $clinic->id)
    )->toBeTrue();
});



it('detects conflict when new schedule contains existing schedule', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '10:00:00',
        'end_time' => '11:00:00',
    ]);

    $schedule->days()->attach($day);



    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor->id,
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'day_ids' => [$day->id],
        ], $clinic->id)
    )->toBeTrue();
});



it('ignores current schedule when ignore id is passed', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
    ]);

    $schedule->days()->attach($day);



    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor->id,
            'start_time' => '09:00:00',
            'end_time' => '12:00:00',
            'day_ids' => [$day->id],
        ], $clinic->id, $schedule->id)
    )->toBeFalse();
});



it('detects conflict if one of multiple days matches', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $day1 = Day::factory()->create();
    $day2 = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
    ]);

    $schedule->days()->attach([
        $day1->id,
        $day2->id,
    ]);



    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor->id,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'day_ids' => [$day2->id],
        ], $clinic->id)
    )->toBeTrue();
});



it('returns false when start time equals existing end time', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();


    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
    ]);

    $schedule->days()->attach($day);



    expect(
        $this->service->hasScheduleConflict([
            'doctor_id' => $doctor->id,
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'day_ids' => [$day->id],
        ], $clinic->id)
    )->toBeFalse();
});