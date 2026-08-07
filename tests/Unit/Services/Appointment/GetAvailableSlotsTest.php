<?php

use App\Enums\ScheduleSlotDuration;
use App\Models\Schedule;
use App\Services\Appointment\AppointmentAvailabilityService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->service = app(AppointmentAvailabilityService::class);
});

it('returns all slots when no slots are booked', function () {

    $schedule = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
        'start_break' => null,
        'end_break' => null,
    ]);

    $result = $this->service->getAvailableSlots([], new Collection([$schedule]));

    expect($result)->toBe([
        '09:00',
        '09:30',
    ]);
});

it('excludes booked slots', function () {

    $schedule = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $result = $this->service->getAvailableSlots(
        ['09:30'],
        new Collection([$schedule])
    );

    expect($result)->toBe([
        '09:00',
    ]);
});

it('returns empty array when all slots are booked', function () {

    $schedule = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $result = $this->service->getAvailableSlots(
        ['09:00', '09:30'],
        new Collection([$schedule])
    );

    expect($result)->toBe([]);
});

it('skips slots during break', function () {

    $schedule = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '11:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
        'start_break' => '10:00:00',
        'end_break' => '10:30:00',
    ]);

    $result = $this->service->getAvailableSlots([], new Collection([$schedule]));

    expect($result)->toBe([
        '09:00',
        '09:30',
        '10:30',
    ]);
});

it('skips overlapping break slots', function () {

    $schedule = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '10:30:00',
        'slot_duration' => ScheduleSlotDuration::SIXTY,
        'start_break' => '09:30:00',
        'end_break' => '10:00:00',
    ]);

    $result = $this->service->getAvailableSlots([], new Collection([$schedule]));

    expect($result)->toBe([]);
});

it('returns slots from multiple schedules', function () {

    $first = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $second = new Schedule([
        'start_time' => '14:00:00',
        'end_time' => '15:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $result = $this->service->getAvailableSlots(
        [],
        new Collection([$first, $second])
    );

    expect($result)->toBe([
        '09:00',
        '09:30',
        '14:00',
        '14:30',
    ]);
});

it('returns empty array when schedules collection is empty', function () {

    $result = $this->service->getAvailableSlots([], new Collection());

    expect($result)->toBe([]);
});

it('does not create partial slot at the end of schedule', function () {

    $schedule = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '09:50:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
    ]);

    $result = $this->service->getAvailableSlots([], new Collection([$schedule]));

    expect($result)->toBe([
        '09:00',
    ]);
});

it('supports fifteen minute slots', function () {

    $schedule = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'slot_duration' => ScheduleSlotDuration::FIFTEEN,
    ]);

    $result = $this->service->getAvailableSlots([], new Collection([$schedule]));

    expect($result)->toBe([
        '09:00',
        '09:15',
        '09:30',
        '09:45',
    ]);
});

it('supports sixty minute slots', function () {

    $schedule = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '12:00:00',
        'slot_duration' => ScheduleSlotDuration::SIXTY,
    ]);

    $result = $this->service->getAvailableSlots([], new Collection([$schedule]));

    expect($result)->toBe([
        '09:00',
        '10:00',
        '11:00',
    ]);
});

it('returns empty when break covers the whole schedule', function () {

    $schedule = new Schedule([
        'start_time' => '09:00:00',
        'end_time' => '10:00:00',
        'slot_duration' => ScheduleSlotDuration::THIRTY,
        'start_break' => '09:00:00',
        'end_break' => '10:00:00',
    ]);

    $result = $this->service->getAvailableSlots([], new Collection([$schedule]));

    expect($result)->toBe([]);
});
