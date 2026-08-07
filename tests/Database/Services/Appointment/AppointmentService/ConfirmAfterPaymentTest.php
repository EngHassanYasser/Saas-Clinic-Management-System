<?php

use App\Enums\AppointmentStatus;
use App\Models\Appointment;
use App\Services\Appointment\AppointmentService;

beforeEach(function () {
    $this->service = app(AppointmentService::class);
});
it('confirms the appointment after successful payment', function () {
    $appointment = Appointment::factory()->create([
        'status' => AppointmentStatus::PENDING,
    ]);

    $this->service->confirmAfterPayment($appointment);

    expect($appointment->fresh()->status)
        ->toBe(AppointmentStatus::CONFIRMED);

    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => AppointmentStatus::CONFIRMED->value,
    ]);
});
it('changes appointment status from pending to confirmed', function () {
    $appointment = Appointment::factory()->create([
        'status' => AppointmentStatus::PENDING,
    ]);

    $this->service->confirmAfterPayment($appointment);

    expect($appointment->fresh()->status)
        ->toBe(AppointmentStatus::CONFIRMED);
});
it('does not modify other appointment data', function () {
    $appointment = Appointment::factory()->create([
        'status' => AppointmentStatus::PENDING,
    ]);

    $doctorId = $appointment->doctor_id;
    $patientId = $appointment->patient_id;
    $visitDate = $appointment->visit_date;

    $this->service->confirmAfterPayment($appointment);

    $appointment->refresh();

    expect($appointment->doctor_id)->toBe($doctorId)
        ->and($appointment->patient_id)->toBe($patientId)
        ->and($appointment->visit_date->toDateString())
        ->toBe($visitDate->toDateString());
});
it('persists confirmed status in database', function () {
    $appointment = Appointment::factory()->create([
        'status' => AppointmentStatus::PENDING,
    ]);

    $this->service->confirmAfterPayment($appointment);

    $this->assertDatabaseHas('appointments', [
        'id' => $appointment->id,
        'status' => AppointmentStatus::CONFIRMED->value,
    ]);
});
