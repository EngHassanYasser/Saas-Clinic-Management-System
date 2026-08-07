<?php

use App\Exceptions\ScheduleConflictException;
use App\Models\Clinic;
use App\Models\Day;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Services\Schedule\ScheduleConflictService;
use App\Services\Schedule\ScheduleService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function makeScheduleUpdateDependencies(): array
{
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $days = Day::factory()
        ->count(3)
        ->create();

    $schedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '08:00:00',
        'end_time' => '12:00:00',
        'start_break' => '10:00:00',
        'end_break' => '10:15:00',
        'is_available' => true,
    ]);

    $schedule->days()->attach([
        $days[0]->id,
        $days[1]->id,
    ]);

    return [
        'clinic' => $clinic,
        'doctor' => $doctor,
        'days' => $days,
        'schedule' => $schedule->fresh(),
    ];
}

function makeScheduleService(bool $hasConflict = false): array
{
    $context = makeScheduleUpdateDependencies();

    $conflictService = Mockery::mock(ScheduleConflictService::class);

    $conflictService
        ->shouldReceive('hasScheduleConflict')
        ->once()
        ->andReturn($hasConflict);

    $service = new ScheduleService($conflictService);

    return [
        ...$context,
        'conflictService' => $conflictService,
        'service' => $service,
    ];
}

/*
|--------------------------------------------------------------------------
| Successful update
|--------------------------------------------------------------------------
*/

it('updates all schedule fields successfully', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $data = [
        'start_time' => '09:00:00',
        'end_time' => '14:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:30:00',
        'is_available' => false,
        'day_ids' => [
            $days[1]->id,
            $days[2]->id,
        ],
    ];

    $updated = $service->update($data, $schedule->id);

    expect($updated)
        ->toBeInstanceOf(Schedule::class)
        ->and($updated->id)->toBe($schedule->id)
        ->and($updated->start_time)->toBe('09:00:00')
        ->and($updated->end_time)->toBe('14:00:00')
        ->and($updated->start_break)->toBe('11:00:00')
        ->and($updated->end_break)->toBe('11:30:00')
        ->and((bool) $updated->is_available)->toBeFalse();

    expect($updated->doctor_id)
        ->toBe($schedule->doctor_id);

    expect($updated->clinic_id)
        ->toBe($schedule->clinic_id);

    expect(
        DB::table('schedules')
            ->where('id', $schedule->id)
            ->where('clinic_id', $schedule->clinic_id)
            ->where('doctor_id', $schedule->doctor_id)
            ->where('start_time', '09:00:00')
            ->where('end_time', '14:00:00')
            ->where('start_break', '11:00:00')
            ->where('end_break', '11:30:00')
            ->where('is_available', false)
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Slot duration
|--------------------------------------------------------------------------
*/

it('updates slot duration correctly', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $data = [
        'start_time' => '09:00:00',
        'end_time' => '13:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => true,
        'day_ids' => [$days[0]->id],
    ];

    $updated = $service->update($data, $schedule->id);

    expect($updated->slot_duration)
        ->toEqual($schedule->slot_duration);

    expect(
        DB::table('schedules')
            ->where('id', $schedule->id)
            ->where(
                'slot_duration',
                $schedule->getRawOriginal('slot_duration')
            )
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Days sync
|--------------------------------------------------------------------------
*/

it('replaces the old days with the new days', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    expect($schedule->days)
        ->toHaveCount(2);

    $data = [
        'start_time' => '09:00:00',
        'end_time' => '14:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => true,
        'day_ids' => [
            $days[2]->id,
        ],
    ];

    $service->update($data, $schedule->id);

    $schedule->refresh();

    expect($schedule->days)
        ->toHaveCount(1)
        ->and($schedule->days->pluck('id')->all())
        ->toBe([$days[2]->id]);

    expect(
        $schedule->days()
            ->whereKey($days[0]->id)
            ->exists()
    )->toBeFalse();

    expect(
        $schedule->days()
            ->whereKey($days[1]->id)
            ->exists()
    )->toBeFalse();

    expect(
        $schedule->days()
            ->whereKey($days[2]->id)
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Add new days
|--------------------------------------------------------------------------
*/

it('can add new days while keeping existing days', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $data = [
        'start_time' => '09:00:00',
        'end_time' => '14:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => true,
        'day_ids' => [
            $days[0]->id,
            $days[1]->id,
            $days[2]->id,
        ],
    ];

    $service->update($data, $schedule->id);

    $schedule->refresh();

    expect($schedule->days)
        ->toHaveCount(3);

    expect($schedule->days->pluck('id')->sort()->values()->all())
        ->toBe(
            collect([
                $days[0]->id,
                $days[1]->id,
                $days[2]->id,
            ])->sort()->values()->all()
        );
});

/*
|--------------------------------------------------------------------------
| Remove all days
|--------------------------------------------------------------------------
*/

it('removes all schedule days when an empty day list is supplied', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];

    $data = [
        'start_time' => '09:00:00',
        'end_time' => '14:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => true,
        'day_ids' => [],
    ];

    $service->update($data, $schedule->id);

    $schedule->refresh();

    expect($schedule->days)
        ->toHaveCount(0);

    expect(
        $schedule->days()->exists()
    )->toBeFalse();
});

/*
|--------------------------------------------------------------------------
| Doctor ID
|--------------------------------------------------------------------------
*/

it('keeps the original doctor id during update', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $anotherDoctor = Doctor::factory()->create();

    $data = [
        'start_time' => '09:00:00',
        'end_time' => '14:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => true,
        'day_ids' => [$days[0]->id],

        'doctor_id' => $anotherDoctor->id,
    ];

    $service->update($data, $schedule->id);

    $schedule->refresh();

    expect($schedule->doctor_id)
        ->toBe($context['doctor']->id)
        ->not->toBe($anotherDoctor->id);

    expect(
        DB::table('schedules')
            ->where('id', $schedule->id)
            ->where('doctor_id', $context['doctor']->id)
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Conflict service receives correct data
|--------------------------------------------------------------------------
*/

it('passes the original doctor id to the conflict service', function () {
    $context = makeScheduleUpdateDependencies();

    $schedule = $context['schedule'];
    $days = $context['days'];

    $conflictService = Mockery::mock(ScheduleConflictService::class);

    $conflictService
        ->shouldReceive('hasScheduleConflict')
        ->once()
        ->withArgs(function (
            array $conflictData,
            int $clinicId,
            int $scheduleId
        ) use ($schedule, $days) {
            return
                $conflictData['doctor_id'] === $schedule->doctor_id
                && $conflictData['start_time'] === '09:00:00'
                && $conflictData['end_time'] === '14:00:00'
                && $conflictData['day_ids'] === [$days[0]->id]
                && $clinicId === $schedule->clinic_id
                && $scheduleId === $schedule->id;
        })
        ->andReturn(false);

    $service = new ScheduleService($conflictService);

    $service->update([
        'start_time' => '09:00:00',
        'end_time' => '14:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => true,
        'day_ids' => [$days[0]->id],
    ], $schedule->id);
});

/*
|--------------------------------------------------------------------------
| Current schedule ID
|--------------------------------------------------------------------------
*/

it('passes the current schedule id to the conflict service', function () {
    $context = makeScheduleUpdateDependencies();

    $schedule = $context['schedule'];
    $days = $context['days'];

    $conflictService = Mockery::mock(ScheduleConflictService::class);

    $conflictService
        ->shouldReceive('hasScheduleConflict')
        ->once()
        ->with(
            Mockery::on(
                fn (array $data) =>
                    $data['doctor_id'] === $schedule->doctor_id
            ),
            $schedule->clinic_id,
            $schedule->id
        )
        ->andReturn(false);

    $service = new ScheduleService($conflictService);

    $service->update([
        'start_time' => '08:00:00',
        'end_time' => '12:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '10:00:00',
        'end_break' => '10:15:00',
        'is_available' => true,
        'day_ids' => [$days[0]->id],
    ], $schedule->id);
});

/*
|--------------------------------------------------------------------------
| Conflict
|--------------------------------------------------------------------------
*/

it('throws ScheduleConflictException when a conflict exists', function () {
    $context = makeScheduleService(true);

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $data = [
        'start_time' => '09:00:00',
        'end_time' => '14:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => false,
        'day_ids' => [$days[2]->id],
    ];

    expect(
        fn () => $service->update($data, $schedule->id)
    )
        ->toThrow(
            ScheduleConflictException::class,
            'يوجد تداخل في جدول العمل.'
        );
});

/*
|--------------------------------------------------------------------------
| Conflict rollback
|--------------------------------------------------------------------------
*/

it('does not update the schedule when a conflict exists', function () {
    $context = makeScheduleService(true);

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $original = $schedule->getAttributes();

    $originalDayIds = $schedule
        ->days()
        ->pluck('days.id')
        ->sort()
        ->values()
        ->all();

    $data = [
        'start_time' => '15:00:00',
        'end_time' => '18:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '16:00:00',
        'end_break' => '16:15:00',
        'is_available' => false,
        'day_ids' => [$days[2]->id],
    ];

    expect(
        fn () => $service->update($data, $schedule->id)
    )->toThrow(ScheduleConflictException::class);

    $schedule->refresh();

    expect($schedule->getAttributes())
        ->toBe($original);

    expect(
        $schedule->days()
            ->pluck('days.id')
            ->sort()
            ->values()
            ->all()
    )->toBe($originalDayIds);
});

/*
|--------------------------------------------------------------------------
| Not found
|--------------------------------------------------------------------------
*/

it('throws ModelNotFoundException when the schedule does not exist', function () {
    $conflictService = Mockery::mock(ScheduleConflictService::class);

    $conflictService
        ->shouldNotReceive('hasScheduleConflict');

    $service = new ScheduleService($conflictService);

    expect(
        fn () => $service->update([
            'start_time' => '09:00:00',
            'end_time' => '14:00:00',
            'slot_duration' => 30,
            'start_break' => '11:00:00',
            'end_break' => '11:15:00',
            'is_available' => true,
            'day_ids' => [],
        ], 999999)
    )->toThrow(ModelNotFoundException::class);
});

/*
|--------------------------------------------------------------------------
| Only requested schedule
|--------------------------------------------------------------------------
*/

it('updates only the requested schedule', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $clinic = $context['clinic'];
    $doctor = $context['doctor'];
    $days = $context['days'];

    $anotherSchedule = Schedule::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'start_time' => '15:00:00',
        'end_time' => '18:00:00',
        'start_break' => '16:00:00',
        'end_break' => '16:15:00',
        'is_available' => true,
    ]);

    $anotherSchedule->days()->attach($days[2]->id);

    $anotherOriginal = $anotherSchedule->fresh()->getAttributes();

    $data = [
        'start_time' => '09:00:00',
        'end_time' => '13:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => false,
        'day_ids' => [$days[0]->id],
    ];

    $service->update($data, $schedule->id);

    $anotherSchedule->refresh();

    expect($anotherSchedule->getAttributes())
        ->toBe($anotherOriginal);

    expect(
        $anotherSchedule->days()
            ->pluck('days.id')
            ->all()
    )->toBe([$days[2]->id]);
});

/*
|--------------------------------------------------------------------------
| Clinic ID
|--------------------------------------------------------------------------
*/

it('keeps the original clinic id during update', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $anotherClinic = Clinic::factory()->create();

    $data = [
        'start_time' => '09:00:00',
        'end_time' => '13:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => true,
        'day_ids' => [$days[0]->id],

        'clinic_id' => $anotherClinic->id,
    ];

    $service->update($data, $schedule->id);

    $schedule->refresh();

    expect($schedule->clinic_id)
        ->toBe($context['clinic']->id)
        ->not->toBe($anotherClinic->id);
});

/*
|--------------------------------------------------------------------------
| Availability
|--------------------------------------------------------------------------
*/

it('can change is_available from false to true', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $schedule->update([
        'is_available' => false,
    ]);

    $schedule->refresh();

    $service->update([
        'start_time' => '09:00:00',
        'end_time' => '13:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => true,
        'day_ids' => [$days[0]->id],
    ], $schedule->id);

    $schedule->refresh();

    expect((bool) $schedule->is_available)
        ->toBeTrue();

    expect(
        DB::table('schedules')
            ->where('id', $schedule->id)
            ->where('is_available', true)
            ->exists()
    )->toBeTrue();
});

it('can change is_available from true to false', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $service->update([
        'start_time' => '09:00:00',
        'end_time' => '13:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => false,
        'day_ids' => [$days[0]->id],
    ], $schedule->id);

    $schedule->refresh();

    expect((bool) $schedule->is_available)
        ->toBeFalse();

    expect(
        DB::table('schedules')
            ->where('id', $schedule->id)
            ->where('is_available', false)
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Fresh model
|--------------------------------------------------------------------------
*/

it('returns a fresh schedule from the database', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];
    $days = $context['days'];

    $updated = $service->update([
        'start_time' => '09:30:00',
        'end_time' => '14:30:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:30:00',
        'end_break' => '11:45:00',
        'is_available' => false,
        'day_ids' => [$days[1]->id],
    ], $schedule->id);

    expect($updated->exists)
        ->toBeTrue();

    expect($updated->is($schedule))
        ->toBeTrue();

    expect($updated->fresh()->getAttributes())
        ->toEqual($updated->getAttributes());
});

/*
|--------------------------------------------------------------------------
| Transaction rollback when sync fails
|--------------------------------------------------------------------------
*/

it('rolls back schedule changes if syncing days fails', function () {
    $context = makeScheduleService();

    $schedule = $context['schedule'];
    $service = $context['service'];

    $originalAttributes = $schedule->getAttributes();

    $originalDayIds = $schedule
        ->days()
        ->pluck('days.id')
        ->sort()
        ->values()
        ->all();

    $data = [
        'start_time' => '09:30:00',
        'end_time' => '14:30:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:30:00',
        'end_break' => '11:45:00',
        'is_available' => false,

        'day_ids' => [PHP_INT_MAX],
    ];

    expect(
        fn () => $service->update($data, $schedule->id)
    )->toThrow(QueryException::class);

    $schedule->refresh();

    expect($schedule->getAttributes())
        ->toBe($originalAttributes);

    expect(
        $schedule->days()
            ->pluck('days.id')
            ->sort()
            ->values()
            ->all()
    )->toBe($originalDayIds);
});

/*
|--------------------------------------------------------------------------
| Multiple updates
|--------------------------------------------------------------------------
*/

it('can update the same schedule more than once', function () {
    $context = makeScheduleUpdateDependencies();

    $schedule = $context['schedule'];
    $days = $context['days'];

    $conflictService = Mockery::mock(ScheduleConflictService::class);

    $conflictService
        ->shouldReceive('hasScheduleConflict')
        ->twice()
        ->andReturn(false);

    $service = new ScheduleService($conflictService);

    $firstUpdate = [
        'start_time' => '09:00:00',
        'end_time' => '13:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '11:00:00',
        'end_break' => '11:15:00',
        'is_available' => false,
        'day_ids' => [$days[0]->id],
    ];

    $secondUpdate = [
        'start_time' => '10:00:00',
        'end_time' => '15:00:00',
        'slot_duration' => $schedule->slot_duration,
        'start_break' => '12:00:00',
        'end_break' => '12:30:00',
        'is_available' => true,
        'day_ids' => [
            $days[1]->id,
            $days[2]->id,
        ],
    ];

    $service->update($firstUpdate, $schedule->id);

    $service->update($secondUpdate, $schedule->id);

    $schedule->refresh();

    expect($schedule->start_time)
        ->toBe('10:00:00')
        ->and($schedule->end_time)
        ->toBe('15:00:00')
        ->and($schedule->start_break)
        ->toBe('12:00:00')
        ->and($schedule->end_break)
        ->toBe('12:30:00')
        ->and((bool) $schedule->is_available)
        ->toBeTrue();

    expect($schedule->days->pluck('id')->sort()->values()->all())
        ->toBe(
            collect([
                $days[1]->id,
                $days[2]->id,
            ])->sort()->values()->all()
        );
});
