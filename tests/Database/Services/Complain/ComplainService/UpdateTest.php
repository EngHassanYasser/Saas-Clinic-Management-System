<?php

use App\Enums\ComplaintStatus;
use App\Models\Clinic;
use App\Models\Complaint;
use App\Models\Doctor;
use App\Services\Complaint\ComplaintService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
beforeEach(function () {
    $this->service = app(ComplaintService::class);
});

it('updates complaint successfully', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $complaint = Complaint::factory()->create([
        'clinic_id' => $clinic->id,
    ]);

    $result = $this->service->update([
        'doctor_id' => $doctor->id,
        'department_name' => 'radiology',
        'visit_date' => '2026-08-06',
        'severity' => 'critical',
        'issue_type' => 'medical',
        'description' => 'Updated description',
        'status' => ComplaintStatus::RESOLVED,
        'patient_name' => 'Ahmed',
        'resolution_notes' => 'Problem solved',
    ], $complaint->id, $clinic->id);

    expect($result)->toBeTrue();

    $complaint->refresh();

    expect($complaint->doctor_id)->toBe($doctor->id)
        ->and($complaint->department)->toBe('radiology')
        ->and($complaint->description)->toBe('Updated description')
        ->and($complaint->patient_name)->toBe('Ahmed')
        ->and($complaint->resolution_notes)->toBe('Problem solved');
});
it('updates complaint in database', function () {

    $clinic = Clinic::factory()->create();

    $complaint = Complaint::factory()->create([
        'clinic_id' => $clinic->id,
    ]);

    $this->service->update([
        'doctor_id' => null,
        'department_name' => 'pharmacy',
        'visit_date' => '2026-08-06',
        'severity' => 'medium',
        'issue_type' => 'billing',
        'description' => 'Updated',
        'status' => ComplaintStatus::UNDER_REVIEW,
        'patient_name' => 'Ali',
        'resolution_notes' => 'Checking',
    ], $complaint->id, $clinic->id);

    $this->assertDatabaseHas('complaintts', [
        'id' => $complaint->id,
        'department' => 'pharmacy',
        'description' => 'Updated',
        'patient_name' => 'Ali',
    ]);
});
it('throws exception when complaint does not exist', function () {

    $clinic = Clinic::factory()->create();

    $this->service->update([
        'department_name' => 'radiology',
        'visit_date' => today(),
        'severity' => 'low',
        'issue_type' => 'other',
        'status' => ComplaintStatus::PENDING,
    ], 999999, $clinic->id);

})->throws(ModelNotFoundException::class);
it('cannot update complaint that belongs to another clinic', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $complaint = Complaint::factory()->create([
        'clinic_id' => $clinic1->id,
    ]);

    $this->service->update([
        'department_name' => 'radiology',
        'visit_date' => today(),
        'severity' => 'medium',
        'issue_type' => 'other',
        'status' => ComplaintStatus::PENDING,
    ], $complaint->id, $clinic2->id);

})->throws(ModelNotFoundException::class);
it('sets nullable fields to null when omitted', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $complaint = Complaint::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'description' => 'Old',
        'patient_name' => 'Ahmed',
        'resolution_notes' => 'Old notes',
    ]);

    $this->service->update([
        'department_name' => 'radiology',
        'visit_date' => today(),
        'severity' => 'medium',
        'issue_type' => 'medical',
        'status' => ComplaintStatus::PENDING,
    ], $complaint->id, $clinic->id);

    $complaint->refresh();
        expect($complaint->doctor_id)->toBeNull()
    ->and($complaint->description)->toBe('Old')
    ->and($complaint->patient_name)->toBeNull()
    ->and($complaint->resolution_notes)->toBeNull();
});
it('updates only requested complaint', function () {

    $clinic = Clinic::factory()->create();

    $first = Complaint::factory()->create([
        'clinic_id' => $clinic->id,
    ]);

    $second = Complaint::factory()->create([
        'clinic_id' => $clinic->id,
        'description' => 'Second',
    ]);

    $this->service->update([
        'department_name' => 'radiology',
        'visit_date' => today(),
        'severity' => 'critical',
        'issue_type' => 'medical',
        'description' => 'Updated',
        'status' => ComplaintStatus::RESOLVED,
    ], $first->id, $clinic->id);

    expect($second->fresh()->description)
        ->toBe('Second');
});
