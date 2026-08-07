<?php

use App\Enums\ScheduleSlotDuration;
use App\Exceptions\ScheduleConflictException;
use App\Models\Clinic;
use App\Models\Day;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Services\Schedule\ScheduleConflictService;
use App\Services\Schedule\ScheduleService;

beforeEach(function () {
    $this->service = app(ScheduleService::class);
});

function scheduleData(array $override = []): array
{
    return array_merge([
        'start_time' => '09:00:00',
        'end_time' => '14:00:00',
        'slot_duration' => 30,
        'start_break' => '11:00:00',
        'end_break' => '11:30:00',
        'is_available' => true,
        'doctor_id' => null,
        'day_ids' => [],
    ], $override);
}


it('creates schedule successfully', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $days = Day::factory()
        ->count(2)
        ->create();

    $schedule = $this->service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
            'day_ids' => $days->pluck('id')->toArray(),
        ]),
        $clinic->id
    );

    expect($schedule)
        ->toBeInstanceOf(Schedule::class);

    expect(Schedule::whereKey($schedule->id)->exists())
        ->toBeTrue();
});


it('stores correct schedule information', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();

    $schedule = $this->service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
            'day_ids' => [$day->id],
            'is_available' => false,
        ]),
        $clinic->id
    );

    expect($schedule->doctor_id)
        ->toBe($doctor->id);

    expect($schedule->clinic_id)
        ->toBe($clinic->id);

    expect($schedule->start_time)
        ->toBe('09:00:00');

    expect($schedule->end_time)
        ->toBe('14:00:00');

    expect($schedule->slot_duration)
        ->toBe(ScheduleSlotDuration::THIRTY);

    expect($schedule->start_break)
        ->toBe('11:00:00');

    expect($schedule->end_break)
        ->toBe('11:30:00');

    expect($schedule->is_available)
        ->toBeFalse();
});


it('attaches selected days to schedule', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $days = Day::factory()
        ->count(3)
        ->create();

    $schedule = $this->service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
            'day_ids' => $days->pluck('id')->toArray(),
        ]),
        $clinic->id
    );

    expect($schedule->days()->count())
        ->toBe(3);

    expect($schedule->days->pluck('id')->toArray())
        ->toEqualCanonicalizing(
            $days->pluck('id')->toArray()
        );
});


it('uses clinic id passed to method instead of clinic id from data', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();

    $schedule = $this->service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
            'clinic_id' => $clinic1->id,
            'day_ids' => [$day->id],
        ]),
        $clinic2->id
    );

    expect($schedule->clinic_id)
        ->toBe($clinic2->id);
});


it('throws conflict exception when schedule conflict exists', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $mock = Mockery::mock(ScheduleConflictService::class);

    $mock->shouldReceive('hasScheduleConflict')
        ->once()
        ->with(
            Mockery::on(function ($data) use ($doctor) {
                return $data['doctor_id'] === $doctor->id
                    && $data['start_time'] === '09:00:00'
                    && $data['end_time'] === '14:00:00';
            }),
            $clinic->id
        )
        ->andReturnTrue();

    $this->app->instance(
        ScheduleConflictService::class,
        $mock
    );

    /*
     * مهم:
     * ScheduleService لازم يتعمل resolve بعد تسجيل الـ mock
     */
    $service = app(ScheduleService::class);

    expect(fn () => $service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
        ]),
        $clinic->id
    ))->toThrow(ScheduleConflictException::class);

    expect(Schedule::count())
        ->toBe(0);
});


it('does not create schedule when conflict exists', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $this->mock(
        ScheduleConflictService::class,
        function ($mock) {

            $mock->shouldReceive('hasScheduleConflict')
                ->once()
                ->andReturnTrue();
        }
    );

    /*
     * Resolve after mock registration.
     */
    $service = app(ScheduleService::class);

    expect(fn () => $service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
        ]),
        $clinic->id
    ))->toThrow(ScheduleConflictException::class);

    expect(Schedule::count())
        ->toBe(0);
});


it('passes correct clinic id to conflict service', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $mock = Mockery::mock(ScheduleConflictService::class);

    $mock->shouldReceive('hasScheduleConflict')
        ->once()
        ->with(
            Mockery::type('array'),
            $clinic->id
        )
        ->andReturnTrue();

    $this->app->instance(
        ScheduleConflictService::class,
        $mock
    );

    $service = app(ScheduleService::class);

    expect(fn () => $service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
        ]),
        $clinic->id
    ))->toThrow(ScheduleConflictException::class);

    expect(Schedule::count())
        ->toBe(0);
});


it('creates schedule with one day', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();
    $day = Day::factory()->create();

    $schedule = $this->service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
            'day_ids' => [$day->id],
        ]),
        $clinic->id
    );

    expect($schedule->days()->count())
        ->toBe(1);

    expect($schedule->days->first()->id)
        ->toBe($day->id);
});


it('creates schedule with multiple days', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $days = Day::factory()
        ->count(5)
        ->create();

    $schedule = $this->service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
            'day_ids' => $days->pluck('id')->toArray(),
        ]),
        $clinic->id
    );

    expect($schedule->days()->count())
        ->toBe(5);

    expect($schedule->days->pluck('id')->toArray())
        ->toEqualCanonicalizing(
            $days->pluck('id')->toArray()
        );
});


it('creates schedule without days when day_ids is empty', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $schedule = $this->service->add(
        scheduleData([
            'doctor_id' => $doctor->id,
            'day_ids' => [],
        ]),
        $clinic->id
    );

    expect($schedule)
        ->toBeInstanceOf(Schedule::class);

    expect($schedule->days()->count())
        ->toBe(0);
});