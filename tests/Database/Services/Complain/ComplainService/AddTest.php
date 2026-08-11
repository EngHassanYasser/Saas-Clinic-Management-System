<?php

use App\Enums\ComplaintStatus;
use App\Enums\EnRoleType;use App\Models\Clinic;
use App\Models\Complaint;
use App\Models\Doctor;
use App\Models\User;
use App\Services\Complaint\ComplaintService;

beforeEach(function () {
    $this->service = app(ComplaintService::class);
});

it('creates complaint and assigns patient as owner when user is patient', function () {

    $patient = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
    ]);

    $data = [
        'doctor_id' => null,
        'department_name' => 'reception',
        'visit_date' => now()->toDateString(),
        'severity' => 'high',
        'issue_type' => 'complaintt',
        'description' => 'Bad experience',
        'status' => ComplaintStatus::PENDING,
        'patient_name' => 'Ahmed',
    ];

    $clinic = Clinic::factory()->create();

    $result = $this->service->add(
        $data,
        $patient,
        $clinic->id
    );

    expect($result)
        ->toBeInstanceOf(Complaint::class)
        ->and($result->user_id)
        ->toBe($patient->id);

    expect(Complaint::where([
        'clinic_id' =>$clinic->id,
        'user_id' => $patient->id,
        'description' => 'Bad experience',
    ])->exists())
        ->toBeTrue();

});
it('creates complaint without user when creator is not patient', function () {

    $clinicUser = User::factory()->create([
        'type' =>EnRoleType::CLINIC,
    ]);
    $clinic = Clinic::factory()->create();

    $data = [
        'doctor_id' => null,
        'department_name' => 'laboratory',
        'visit_date' => now()->toDateString(),
        'severity' => 'medium',
        'issue_type' => 'technical_issue',
        'description' => 'Machine problem',
        'status' => ComplaintStatus::PENDING,
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
it('stores complaint data correctly', function () {

    $patient = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
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
        'status' => ComplaintStatus::PENDING,
        'patient_name' => 'Ali',
    ];

    $complaint = $this->service->add(
        $data,
        $patient,
        $clinic->id
    );
    expect($complaint->toArray())
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
it('stores complaint in database', function () {

    $user = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
    ]);
    $clinic = Clinic::factory()->create();

    $complaint = $this->service->add(
        [
            'doctor_id' => null,
            'department_name' => 'pharmacy',
            'visit_date' => now()->toDateString(),
            'severity' => 'low',
            'issue_type' => 'suggestion',
            'description' => 'Improve pharmacy',
            'status' => ComplaintStatus::PENDING,
            'patient_name' => 'Test',
        ],
        $user,
        $clinic->id
    );

    expect($complaint->exists)
        ->toBeTrue();

    $this->assertDatabaseHas('complaints', [
        'id' => $complaint->id,
        'clinic_id' => $clinic->id,
        'description' => 'Improve pharmacy',
    ]);

});
it('ignores provided user_id and uses authenticated patient id', function () {

    $patient = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
    ]);
    $clinic = Clinic::factory()->create();

    $data = [
        'user_id' => 999,
        'doctor_id' => null,
        'department_name' => 'reception',
        'visit_date' => now()->toDateString(),
        'severity' => 'low',
        'issue_type' => 'complaintt',
        'description' => 'test',
        'status' => ComplaintStatus::PENDING,
        'patient_name' => 'Test',
    ];
    $complaint = $this->service->add(
        $data,
        $patient,
        $clinic->id
    );

    expect($complaint->user_id)
        ->toBe($patient->id)
        ->not->toBe(999);

});
it('returns created complaint model', function () {

    $user = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
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
            'status' => ComplaintStatus::PENDING,
            'patient_name' => 'Test',
        ],
        $user,
        $clinic->id,

    );

    expect($result)
        ->toBeInstanceOf(Complaint::class);

});
