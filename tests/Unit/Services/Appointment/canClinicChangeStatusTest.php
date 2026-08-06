<?php

use App\Enums\AppointmentStatus;
use App\Services\Appointment\AppointmentStatusService;
use ReflectionClass;

it('checks if clinic can change appointment status', function (
    AppointmentStatus $currentStatus,
    AppointmentStatus $newStatus,
    bool $expected
) {
    $service = app(AppointmentStatusService::class);

    $reflection = new ReflectionClass($service);

    $method = $reflection->getMethod('canClinicChangeStatus');

    $result = $method->invoke(
        $service,
        $currentStatus,
        $newStatus
    );

    expect($result)->toBe($expected);
})->with([

    // PENDING
    'pending -> confirmed' => [
        AppointmentStatus::PENDING,
        AppointmentStatus::CONFIRMED,
        true,
    ],

    'pending -> cancelled' => [
        AppointmentStatus::PENDING,
        AppointmentStatus::CANCELLED,
        true,
    ],

    'pending -> completed' => [
        AppointmentStatus::PENDING,
        AppointmentStatus::COMPLETED,
        false,
    ],

    // CONFIRMED
    'confirmed -> in progress' => [
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::IN_PROGRESS,
        true,
    ],

    'confirmed -> cancelled' => [
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::CANCELLED,
        true,
    ],

    'confirmed -> no show' => [
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::NO_SHOW,
        true,
    ],

    'confirmed -> rescheduled' => [
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::RESCHEDULED,
        true,
    ],

    'confirmed -> completed' => [
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::COMPLETED,
        false,
    ],

    // IN_PROGRESS
    'in progress -> completed' => [
        AppointmentStatus::IN_PROGRESS,
        AppointmentStatus::COMPLETED,
        true,
    ],

    'in progress -> cancelled' => [
        AppointmentStatus::IN_PROGRESS,
        AppointmentStatus::CANCELLED,
        false,
    ],

    // Default cases
    'completed -> cancelled' => [
        AppointmentStatus::COMPLETED,
        AppointmentStatus::CANCELLED,
        false,
    ],

    'cancelled -> confirmed' => [
        AppointmentStatus::CANCELLED,
        AppointmentStatus::CONFIRMED,
        false,
    ],

    'no show -> completed' => [
        AppointmentStatus::NO_SHOW,
        AppointmentStatus::COMPLETED,
        false,
    ],

    'rescheduled -> confirmed' => [
        AppointmentStatus::RESCHEDULED,
        AppointmentStatus::CONFIRMED,
        false,
    ],
]);