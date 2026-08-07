<?php

use App\Enums\ComplainStatus;
use App\Enums\RoleType;
use App\Models\Clinic;
use App\Models\Complain;
use App\Models\Doctor;
use App\Models\User;
use App\Services\Complain\ComplainService;

beforeEach(function () {
    $this->service = app(ComplainService::class);
});

it('creates complain and assigns patient as owner when user is patient', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);

    $data = [
        'doctor_id' => null,
        'department_name' => 'reception',
        'visit_date' => now()->toDateString(),
        'severity' => 'high',
        'issue_type' => 'complaint',
        'description' => 'Bad experience',
        'status' => ComplainStatus::PENDING,
        'patient_name' => 'Ahmed',
    ];

    $clinic = Clinic::factory()->create();

    $result = $this->service->add(
        $data,
        $patient,
        $clinic->id
    );

    expect($result)
        ->toBeInstanceOf(Complain::class)
        ->and($result->user_id)
        ->toBe($patient->id);

    expect(Complain::where([
        'clinic_id' =>$clinic->id,
        'user_id' => $patient->id,
        'description' => 'Bad experience',
    ])->exists())
        ->toBeTrue();

});
it('creates complain without user when creator is not patient', function () {

    $clinicUser = User::factory()->create([
        'type' => RoleType::CLINIC,
    ]);
    $clinic = Clinic::factory()->create();

    $data = [
        'doctor_id' => null,
        'department_name' => 'laboratory',
        'visit_date' => now()->toDateString(),
        'severity' => 'medium',
        'issue_type' => 'technical_issue',
        'description' => 'Machine problem',
        'status' => ComplainStatus::PENDING,
        'patient_name' => 'Mohamed',
    ];

    $result = $this->service->add(
        $data,
        $clinicUser,
        $clinic->id
    );

    expect($result->user_id)
        ->toBeNull();

});
it('stores complain data correctly', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $data = [
        'doctor_id' => $doctor->id,
        'department_name' => 'radiology',
        'visit_date' => '2026-08-06',
        'severity' => 'critical',
        'issue_type' => 'medical',
        'description' => 'Medical issue',
        'status' => ComplainStatus::PENDING,
        'patient_name' => 'Ali',
    ];

    $complain = $this->service->add(
        $data,
        $patient,
        $clinic->id
    );
    expect($complain->toArray())
        ->toMatchArray([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'department' => 'radiology',
            'visit_date' => '2026-08-06',
            'severity' => 'critical',
            'issue_type' => 'medical',
            'description' => 'Medical issue',
            'patient_name' => 'Ali',
        ]);

});
it('stores complain in database', function () {

    $user = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);
    $clinic = Clinic::factory()->create();

    $complain = $this->service->add(
        [
            'doctor_id' => null,
            'department_name' => 'pharmacy',
            'visit_date' => now()->toDateString(),
            'severity' => 'low',
            'issue_type' => 'suggestion',
            'description' => 'Improve pharmacy',
            'status' => ComplainStatus::PENDING,
            'patient_name' => 'Test',
        ],
        $user,
        $clinic->id
    );

    expect($complain->exists)
        ->toBeTrue();

    $this->assertDatabaseHas('complains', [
        'id' => $complain->id,
        'clinic_id' => $clinic->id,
        'description' => 'Improve pharmacy',
    ]);

});
it('ignores provided user_id and uses authenticated patient id', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);
    $clinic = Clinic::factory()->create();

    $data = [
        'user_id' => 999,
        'doctor_id' => null,
        'department_name' => 'reception',
        'visit_date' => now()->toDateString(),
        'severity' => 'low',
        'issue_type' => 'complaint',
        'description' => 'test',
        'status' => ComplainStatus::PENDING,
        'patient_name' => 'Test',
    ];
    $complain = $this->service->add(
        $data,
        $patient,
        $clinic->id
    );

    expect($complain->user_id)
        ->toBe($patient->id)
        ->not->toBe(999);

});
it('returns created complain model', function () {

    $user = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);

    $clinic = Clinic::factory()->create();

    $result = $this->service->add(
        [
            'doctor_id' => null,
            'department_name' => 'nursing',
            'visit_date' => now()->toDateString(),
            'severity' => 'medium',
            'issue_type' => 'other',
            'description' => 'test',
            'status' => ComplainStatus::PENDING,
            'patient_name' => 'Test',
        ],
        $user,
        $clinic->id,

    );

    expect($result)
        ->toBeInstanceOf(Complain::class);

});
