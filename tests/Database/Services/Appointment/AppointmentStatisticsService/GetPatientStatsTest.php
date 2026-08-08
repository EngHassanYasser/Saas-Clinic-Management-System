<?php
use App\Models\Appointment;
use App\Models\User;
use App\Services\Appointment\AppointmentStatisticsService;

beforeEach(function () {
    $this->service = App(AppointmentStatisticsService::class);
});
it('returns patient appointment statistics', function () {

    $patient = User::factory()->create();
    $otherPatient = User::factory()->create();

    Appointment::factory()->count(2)->pending()->create([
        'patient_id' => $patient->id,
    ]);

    Appointment::factory()->count(3)->confirmed()->create([
        'patient_id' => $patient->id,
    ]);

    Appointment::factory()->completed()->create([
        'patient_id' => $patient->id,
    ]);

    // يجب ألا تدخل في الحسابات
    Appointment::factory()->count(5)->cancelled()->create([
        'patient_id' => $otherPatient->id,
    ]);

    $statistics = $this->service->getPatientStats($patient->id);

    expect($statistics['total'])->toBe(6)
        ->and($statistics['pending'])->toBe(2)
        ->and($statistics['confirmed'])->toBe(3)
        ->and($statistics['completed'])->toBe(1)
        ->and($statistics['cancelled'])->toBe(0);
});
it('returns zero statistics when patient has no appointments', function () {

    $patient = User::factory()->create();

    $statistics = $this->service->getPatientStats($patient->id);

    expect($statistics['total'])->toBe(0)
        ->and($statistics['pending'])->toBe(0)
        ->and($statistics['confirmed'])->toBe(0)
        ->and($statistics['completed'])->toBe(0)
        ->and($statistics['cancelled'])->toBe(0);
});