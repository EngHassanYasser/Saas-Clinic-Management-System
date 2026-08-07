<?php
use App\Enums\RoleType;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use App\Services\Appointment\AppointmentStatisticsService;

beforeEach(function () {
    $this->service = App(AppointmentStatisticsService::class);
});
it('returns patient statistics when user type is patient', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);

    Appointment::factory()->count(2)->pending()->create([
        'patient_id' => $patient->id,
    ]);

    Appointment::factory()->confirmed()->create([
        'patient_id' => $patient->id,
    ]);

    Appointment::factory()->completed()->create([
        'patient_id' => $patient->id,
    ]);

    $stats = $this->service->getStats($patient);

    expect($stats->total)->toBe(4)
        ->and($stats->pending)->toBe(2)
        ->and($stats->confirmed)->toBe(1)
        ->and($stats->completed)->toBe(1)
        ->and($stats->cancelled)->toBe(0);
});
it('returns clinic statistics when user type is clinic', function () {

    $owner = User::factory()->create([
        'type' => RoleType::CLINIC,
    ]);

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    Appointment::factory()->count(3)->confirmed()->create([
        'clinic_id' => $clinic->id,
    ]);

    Appointment::factory()->completed()->create([
        'clinic_id' => $clinic->id,
    ]);

    $stats = $this->service->getStats($owner);

    expect($stats->total)->toBe(4)
        ->and($stats->pending)->toBe(0)
        ->and($stats->confirmed)->toBe(3)
        ->and($stats->completed)->toBe(1)
        ->and($stats->cancelled)->toBe(0);
});
it('does not include other clinics appointments', function () {

    $owner = User::factory()->create([
        'type' => RoleType::CLINIC,
    ]);

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $otherClinic = Clinic::factory()->create();

    Appointment::factory()->confirmed()->create([
        'clinic_id' => $clinic->id,
    ]);

    Appointment::factory()->count(5)->completed()->create([
        'clinic_id' => $otherClinic->id,
    ]);

    $stats = $this->service->getStats($owner);

    expect($stats->total)->toBe(1)
        ->and($stats->completed)->toBe(0);
});
it('returns empty appointment when role is not supported', function () {

    $user = User::factory()->create([
        'type' => RoleType::SUPER_ADMIN,
    ]);

    $result = $this->service->getStats($user);

    expect($result)->toBeInstanceOf(Appointment::class)
        ->and($result->exists)->toBeFalse();
});