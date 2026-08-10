<?php

use App\Models\User;
use App\Models\Complaint;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Enums\EnRoleType;use App\Services\Complaint\ComplaintQueryService;


beforeEach(function () {
    $this->service = app(ComplaintQueryService::class);
});


it('returns only clinic complaintts for clinic owner', function () {

    $clinicOwner = User::factory()->create([
        'type' =>EnRoleType::CLINIC,
    ]);

    $otherClinicOwner = User::factory()->create([
        'type' =>EnRoleType::CLINIC,
    ]);


    $clinic = Clinic::factory()->create([
        'owner_id' => $clinicOwner->id
    ]);


    $otherClinic = Clinic::factory()->create([
        'owner_id' => $otherClinicOwner->id
    ]);


    $firstComplaint = Complaint::factory()->create([
        'clinic_id' => $clinic->id
    ]);


    $secondComplaint = Complaint::factory()->create([
        'clinic_id' => $otherClinic->id
    ]);


    $result = $this->service->getClinicComplaints($clinicOwner);


    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->id)
        ->toBe($firstComplaint->id);

});
it('returns only complaintts created by patient', function () {

    $patient = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
    ]);


    $otherPatient = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
    ]);


    $clinic = Clinic::factory()->create();


    $patientComplaint = Complaint::factory()->create([
        'clinic_id' => $clinic->id,
        'user_id' => $patient->id,
    ]);


    Complaint::factory()->create([
        'clinic_id' => $clinic->id,
        'user_id' => $otherPatient->id,
    ]);


    $result = $this->service->getClinicComplaints($patient);


    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->id)
        ->toBe($patientComplaint->id);

});
it('loads patient and doctor relationships', function () {

    $patient = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
    ]);

    $doctor = Doctor::factory()->create();


    $complaint = Complaint::factory()->create([
        'user_id' => $patient->id,
        'doctor_id' => $doctor->id
    ]);


    $result = $this->service->getClinicComplaints($patient);


    $item = $result->first();


    expect($item->relationLoaded('patient'))
        ->toBeTrue()
        ->and($item->relationLoaded('doctor'))
        ->toBeTrue();

});
it('returns only required columns', function () {

    $patient = User::factory()->create([
        'type' =>EnRoleType::PATIENT,
    ]);

    $complaint = Complaint::factory()->create([
        'user_id' => $patient->id,
    ]);


    $result = $this->service->getClinicComplaints($patient);


    $item = $result->first();


    expect($item)->not->toBeNull();


    expect($item->getAttributes())
        ->not->toHaveKey('some_hidden_column');

});
it('returns all complaintts for other roles', function () {

    $admin = User::factory()->create([
        'type' =>EnRoleType::SUPER_ADMIN,
    ]);


    Complaint::factory(5)->create();


    $result = $this->service->getClinicComplaints($admin);


    expect($result)->toHaveCount(5);

});
it('returns empty collection if clinic has no owner match', function () {

    $clinicUser = User::factory()->create([
        'type' =>EnRoleType::CLINIC,
    ]);


    Complaint::factory()->create();


    $result = $this->service->getClinicComplaints($clinicUser);


    expect($result)->toBeEmpty();

});