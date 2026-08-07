<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Services\Schedule\ScheduleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;


beforeEach(function () {
    $this->service = app(ScheduleService::class);
});

it('deletes the schedule successfully', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
    ]);

    $result = $this->service->delete(
        $schedule->id,
        $clinic->id
    );

    expect($result)
        ->toBeTrue();

    expect(Schedule::whereKey($schedule->id)->exists())
        ->toBeFalse();
});


it('returns true when the schedule is deleted', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
    ]);

    $result = $this->service->delete(
        $schedule->id,
        $clinic->id
    );

    expect($result)->toBeTrue();
});


it('deletes only the requested schedule', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $schedule1 = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
    ]);

    $schedule2 = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
    ]);

    $this->service->delete(
        $schedule1->id,
        $clinic->id
    );

    expect(Schedule::whereKey($schedule1->id)->exists())
        ->toBeFalse();

    expect(Schedule::whereKey($schedule2->id)->exists())
        ->toBeTrue();
});


it('does not delete a schedule belonging to another clinic', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic1->id,
        'doctor_id' => $doctor->id,
    ]);

    expect(fn () => $this->service->delete(
        $schedule->id,
        $clinic2->id
    ))->toThrow(ModelNotFoundException::class);

    expect(Schedule::whereKey($schedule->id)->exists())
        ->toBeTrue();
});


it('throws ModelNotFoundException when schedule does not exist', function () {

    $clinic = Clinic::factory()->create();

    expect(fn () => $this->service->delete(
        999999,
        $clinic->id
    ))->toThrow(ModelNotFoundException::class);

    expect(Schedule::count())
        ->toBe(0);
});


it('throws ModelNotFoundException when schedule id exists but belongs to another clinic', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic1->id,
        'doctor_id' => $doctor->id,
    ]);

    expect(fn () => $this->service->delete(
        $schedule->id,
        $clinic2->id
    ))->toThrow(ModelNotFoundException::class);

    expect(Schedule::whereKey($schedule->id)->exists())
        ->toBeTrue();
});


it('deletes the correct schedule when multiple clinics have schedules', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic1Schedule = Schedule::factory()->create([
        'clinic_id' => $clinic1->id,
        'doctor_id' => $doctor->id,
    ]);

    $clinic2Schedule = Schedule::factory()->create([
        'clinic_id' => $clinic2->id,
        'doctor_id' => $doctor->id,
    ]);

    $this->service->delete(
        $clinic1Schedule->id,
        $clinic1->id
    );

    expect(Schedule::whereKey($clinic1Schedule->id)->exists())
        ->toBeFalse();

    expect(Schedule::whereKey($clinic2Schedule->id)->exists())
        ->toBeTrue();
});


it('does not delete any schedule when clinic id is incorrect', function () {

    $clinic = Clinic::factory()->create();
    $wrongClinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
    ]);

    expect(fn () => $this->service->delete(
        $schedule->id,
        $wrongClinic->id
    ))->toThrow(ModelNotFoundException::class);

    expect(Schedule::count())
        ->toBe(1);

    expect(Schedule::whereKey($schedule->id)->exists())
        ->toBeTrue();
});


it('does not delete schedules belonging to other clinics when deleting one schedule', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic1Schedules = Schedule::factory()
        ->count(3)
        ->create([
            'clinic_id' => $clinic1->id,
            'doctor_id' => $doctor->id,
        ]);

    $clinic2Schedules = Schedule::factory()
        ->count(3)
        ->create([
            'clinic_id' => $clinic2->id,
            'doctor_id' => $doctor->id,
        ]);

    $target = $clinic1Schedules->first();

    $this->service->delete(
        $target->id,
        $clinic1->id
    );

    expect(Schedule::whereKey($target->id)->exists())
        ->toBeFalse();

    foreach ($clinic1Schedules->skip(1) as $schedule) {
        expect(Schedule::whereKey($schedule->id)->exists())
            ->toBeTrue();
    }

    foreach ($clinic2Schedules as $schedule) {
        expect(Schedule::whereKey($schedule->id)->exists())
            ->toBeTrue();
    }
});


it('does not delete a schedule when only the clinic id is wrong', function () {

    $correctClinic = Clinic::factory()->create();
    $wrongClinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $schedule = Schedule::factory()->create([
        'clinic_id' => $correctClinic->id,
        'doctor_id' => $doctor->id,
    ]);

    expect(fn () => $this->service->delete(
        $schedule->id,
        $wrongClinic->id
    ))->toThrow(ModelNotFoundException::class);

    expect(
        Schedule::where('clinic_id', $correctClinic->id)->count()
    )->toBe(1);
});


it('deletes a schedule even when it has related data', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
    ]);

    $this->service->delete(
        $schedule->id,
        $clinic->id
    );

    expect(Schedule::whereKey($schedule->id)->exists())
        ->toBeFalse();
});
