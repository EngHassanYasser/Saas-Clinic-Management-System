<?php

use App\Enums\AppointmentStatus;
use App\Enums\EnRoleType;use App\Exceptions\UnauthorizedException;
use App\Models\Appointment;
use App\Models\User;
use App\Services\Appointment\AppointmentStatusService;

it('does not throw exception for allowed status change', function (
   EnRoleType $role,
    AppointmentStatus $currentStatus,
    AppointmentStatus $newStatus
) {
    $service = app(AppointmentStatusService::class);

    $appointment = Appointment::factory()->make([
        'status' => $currentStatus,
    ]);

    $user = User::factory()->make([
        'type' => $role,
    ]);

    $method = (new ReflectionClass($service))
        ->getMethod('authorizeStatusChange');

    $method->invoke(
        $service,
        $appointment,
        $newStatus,
        $user
    );

    expect(true)->toBeTrue();
})->with([
    'patient allowed' => [
       EnRoleType::PATIENT,
        AppointmentStatus::PENDING,
        AppointmentStatus::CANCELLED,
    ],

    'clinic allowed' => [
       EnRoleType::CLINIC,
        AppointmentStatus::CONFIRMED,
        AppointmentStatus::IN_PROGRESS,
    ],
]);
it('throws unauthorized exception for invalid status change', function (
   EnRoleType $role,
    AppointmentStatus $currentStatus,
    AppointmentStatus $newStatus
) {
    $service = app(AppointmentStatusService::class);

    $appointment = Appointment::factory()->make([
        'status' => $currentStatus,
    ]);

    $user = User::factory()->make([
        'type' => $role,
    ]);

    $this->expectException(UnauthorizedException::class);
    
    $method = (new ReflectionClass($service))
        ->getMethod('authorizeStatusChange');

    $method->invoke(
        $service,
        $appointment,
        $newStatus,
        $user
    );

})->with([
    'patient denied' => [
       EnRoleType::PATIENT,
        AppointmentStatus::PENDING,
        AppointmentStatus::COMPLETED,
    ],

    'clinic denied' => [
       EnRoleType::CLINIC,
        AppointmentStatus::IN_PROGRESS,
        AppointmentStatus::CANCELLED,
    ],
]);
