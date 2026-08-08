<?php

use App\Models\Appointment;
use App\Models\Clinic;
use App\Services\Appointment\AppointmentStatisticsService;

beforeEach(function () {
    $this->service = App(AppointmentStatisticsService::class);
});
it('returns clinic appointment statistics', function () {

    $clinic = Clinic::factory()->create();
    $otherClinic = Clinic::factory()->create();

    Appointment::factory()->count(2)->pending()->create([
        'clinic_id' => $clinic->id,
    ]);

    Appointment::factory()->confirmed()->create([
        'clinic_id' => $clinic->id,
    ]);

    Appointment::factory()->count(5)->completed()->create([
        'clinic_id' => $otherClinic->id,
    ]);

    $statistics = $this->service->getClinicStats($clinic->id);

    expect($statistics['total'])->toBe(3)
        ->and($statistics['pending'])->toBe(2)
        ->and($statistics['confirmed'])->toBe(1)
        ->and($statistics['completed'])->toBe(0)
        ->and($statistics['cancelled'])->toBe(0);
});
