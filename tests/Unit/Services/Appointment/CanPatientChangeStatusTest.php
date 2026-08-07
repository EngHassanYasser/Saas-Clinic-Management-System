<?php
use App\Enums\AppointmentStatus;
use App\Services\Appointment\AppointmentStatusService;
it('checks if patient can change appointment status', function (
    AppointmentStatus $currentStatus,
    AppointmentStatus $newStatus,
    bool $expected
) {

    $service = app(AppointmentStatusService::class);

    $reflection = new ReflectionClass($service);

    $method = $reflection->getMethod('canPatientChangeStatus');

    $result = $method->invoke(
        $service,
        $currentStatus,
        $newStatus
    );
    expect($result)->toBe($expected);
})->with([

    // allowed transitions

    [
        AppointmentStatus::PENDING,
        AppointmentStatus::CANCELLED,
        true
    ],

    [
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::CANCELLED,
        true
    ],


    // blocked transitions

    [
        AppointmentStatus::COMPLETED,
        AppointmentStatus::CANCELLED,
        false
    ],

    [
        AppointmentStatus::CANCELLED,
        AppointmentStatus::CANCELLED,
        false
    ],

    [
        AppointmentStatus::PENDING,
        AppointmentStatus::COMPLETED,
        false
    ],

    [
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::COMPLETED,
        false
    ],

]);