<?php

use App\Enums\ComplainStatus;
use App\Models\Clinic;
use App\Models\Complain;
use App\Models\Doctor;
use App\Services\Complain\ComplainService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
beforeEach(function () {
    $this->service = app(ComplainService::class);
});

it('updates complain successfully', function () {

    $clinic = Clinic::factory()->create();
    $doctor = Doctor::factory()->create();

    $complain = Complain::factory()->create([
        'clinic_id' => $clinic->id,
    ]);

    $result = $this->service->update([
        'doctor_id' => $doctor->id,
        'department_name' => 'radiology',
        'visit_date' => '2026-08-06',
        'severity' => 'critical',
        'issue_type' => 'medical',
        'description' => 'Updated description',
        'status' => ComplainStatus::RESOLVED,
        'patient_name' => 'Ahmed',
        'resolution_notes' => 'Problem solved',
    ], $complain->id, $clinic->id);

    expect($result)->toBeTrue();

    $complain->refresh();

    expect($complain->doctor_id)->toBe($doctor->id)
        ->and($complain->department)->toBe('radiology')
        ->and($complain->description)->toBe('Updated description')
        ->and($complain->patient_name)->toBe('Ahmed')
        ->and($complain->resolution_notes)->toBe('Problem solved');
});
it('updates complain in database', function () {

    $clinic = Clinic::factory()->create();

    $complain = Complain::factory()->create([
        'clinic_id' => $clinic->id,
    ]);

    $this->service->update([
        'doctor_id' => null,
        'department_name' => 'pharmacy',
        'visit_date' => '2026-08-06',
        'severity' => 'medium',
        'issue_type' => 'billing',
        'description' => 'Updated',
        'status' => ComplainStatus::UNDER_REVIEW,
        'patient_name' => 'Ali',
        'resolution_notes' => 'Checking',
    ], $complain->id, $clinic->id);

    $this->assertDatabaseHas('complains', [
        'id' => $complain->id,
        'department' => 'pharmacy',
        'description' => 'Updated',
        'patient_name' => 'Ali',
    ]);
});
it('throws exception when complain does not exist', function () {

    $clinic = Clinic::factory()->create();

    $this->service->update([
        'department_name' => 'radiology',
        'visit_date' => today(),
        'severity' => 'low',
        'issue_type' => 'other',
        'status' => ComplainStatus::PENDING,
    ], 999999, $clinic->id);

})->throws(ModelNotFoundException::class);
it('cannot update complain that belongs to another clinic', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $complain = Complain::factory()->create([
        'clinic_id' => $clinic1->id,
    ]);

    $this->service->update([
        'department_name' => 'radiology',
        'visit_date' => today(),
        'severity' => 'medium',
        'issue_type' => 'other',
        'status' => ComplainStatus::PENDING,
    ], $complain->id, $clinic2->id);

})->throws(ModelNotFoundException::class);
it('sets nullable fields to null when omitted', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $complain = Complain::factory()->create([
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
        'status' => ComplainStatus::PENDING,
    ], $complain->id, $clinic->id);

    $complain->refresh();
        expect($complain->doctor_id)->toBeNull()
    ->and($complain->description)->toBe('Old')
    ->and($complain->patient_name)->toBeNull()
    ->and($complain->resolution_notes)->toBeNull();
});
it('updates only requested complain', function () {

    $clinic = Clinic::factory()->create();

    $first = Complain::factory()->create([
        'clinic_id' => $clinic->id,
    ]);

    $second = Complain::factory()->create([
        'clinic_id' => $clinic->id,
        'description' => 'Second',
    ]);

    $this->service->update([
        'department_name' => 'radiology',
        'visit_date' => today(),
        'severity' => 'critical',
        'issue_type' => 'medical',
        'description' => 'Updated',
        'status' => ComplainStatus::RESOLVED,
    ], $first->id, $clinic->id);

    expect($second->fresh()->description)
        ->toBe('Second');
});
