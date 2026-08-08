<?php

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Services\Appointment\AppointmentStatisticsService;

beforeEach(function () {
    $this->service = App(AppointmentStatisticsService::class);
});
it('returns correct statistics for clinic', function () {

    $clinic = Clinic::factory()->create();
    $otherClinic = Clinic::factory()->create();

    Appointment::factory()->count(2)->pending()->create([
        'clinic_id' => $clinic->id,
    ]);

    Appointment::factory()->count(3)->confirmed()->create([
        'clinic_id' => $clinic->id,
    ]);

    Appointment::factory()->completed()->create([
        'clinic_id' => $clinic->id,
    ]);

    Appointment::factory()->cancelled()->create([
        'clinic_id' => $clinic->id,
    ]);

    // لا يجب احتسابها
    Appointment::factory()->count(5)->confirmed()->create([
        'clinic_id' => $otherClinic->id,
    ]);

    $statistics = $this->service
        ->getAppointmentsStatisticsBy('clinic_id', $clinic->id);

    expect($statistics['total'])->toBe(7)
        ->and($statistics['pending'])->toBe(2)
        ->and($statistics['confirmed'])->toBe(3)
        ->and($statistics['completed'])->toBe(1)
        ->and($statistics['cancelled'])->toBe(1);
});
it('returns zero statistics when clinic has no appointments', function () {

    $clinic = Clinic::factory()->create();

    $statistics = $this->service
        ->getAppointmentsStatisticsBy('clinic_id', $clinic->id);

    expect($statistics['total'])->toBe(0)
        ->and($statistics['pending'])->toBe(0)
        ->and($statistics['confirmed'])->toBe(0)
        ->and($statistics['completed'])->toBe(0)
        ->and($statistics['cancelled'])->toBe(0);
});
it('filters statistics by doctor', function () {

    $doctor = Doctor::factory()->create();
    $otherDoctor = Doctor::factory()->create();

    Appointment::factory()->count(2)->confirmed()->create([
        'doctor_id' => $doctor->id,
    ]);

    Appointment::factory()->pending()->create([
        'doctor_id' => $doctor->id,
    ]);

    Appointment::factory()->count(4)->completed()->create([
        'doctor_id' => $otherDoctor->id,
    ]);

    $statistics = $this->service
        ->getAppointmentsStatisticsBy('doctor_id', $doctor->id);

    expect($statistics['total'])->toBe(3)
        ->and($statistics['pending'])->toBe(1)
        ->and($statistics['confirmed'])->toBe(2)
        ->and($statistics['completed'])->toBe(0)
        ->and($statistics['cancelled'])->toBe(0);
});
it('returns correct statistics when all appointments have same status', function () {

    $clinic = Clinic::factory()->create();

    Appointment::factory()->count(5)->completed()->create([
        'clinic_id' => $clinic->id,
    ]);

    $statistics = $this->service
        ->getAppointmentsStatisticsBy('clinic_id', $clinic->id);

    expect($statistics['total'])->toBe(5)
        ->and($statistics['pending'])->toBe(0)
        ->and($statistics['confirmed'])->toBe(0)
        ->and($statistics['completed'])->toBe(5)
        ->and($statistics['cancelled'])->toBe(0);
});
