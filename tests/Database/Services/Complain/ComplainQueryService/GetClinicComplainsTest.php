<?php

use App\Models\User;
use App\Models\Complain;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Enums\RoleType;
use App\Services\Complain\ComplainQueryService;


beforeEach(function () {
    $this->service = app(ComplainQueryService::class);
});


it('returns only clinic complains for clinic owner', function () {

    $clinicOwner = User::factory()->create([
        'type' => RoleType::CLINIC,
    ]);

    $otherClinicOwner = User::factory()->create([
        'type' => RoleType::CLINIC,
    ]);


    $clinic = Clinic::factory()->create([
        'owner_id' => $clinicOwner->id
    ]);


    $otherClinic = Clinic::factory()->create([
        'owner_id' => $otherClinicOwner->id
    ]);


    $firstComplain = Complain::factory()->create([
        'clinic_id' => $clinic->id
    ]);


    $secondComplain = Complain::factory()->create([
        'clinic_id' => $otherClinic->id
    ]);


    $result = $this->service->getClinicComplains($clinicOwner);


    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->id)
        ->toBe($firstComplain->id);

});
it('returns only complains created by patient', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);


    $otherPatient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);


    $clinic = Clinic::factory()->create();


    $patientComplain = Complain::factory()->create([
        'clinic_id' => $clinic->id,
        'user_id' => $patient->id,
    ]);


    Complain::factory()->create([
        'clinic_id' => $clinic->id,
        'user_id' => $otherPatient->id,
    ]);


    $result = $this->service->getClinicComplains($patient);


    expect($result)
        ->toHaveCount(1)
        ->and($result->first()->id)
        ->toBe($patientComplain->id);

});
it('loads patient and doctor relationships', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);

    $doctor = Doctor::factory()->create();


    $complain = Complain::factory()->create([
        'user_id' => $patient->id,
        'doctor_id' => $doctor->id
    ]);


    $result = $this->service->getClinicComplains($patient);


    $item = $result->first();


    expect($item->relationLoaded('patient'))
        ->toBeTrue()
        ->and($item->relationLoaded('doctor'))
        ->toBeTrue();

});
it('returns only required columns', function () {

    $patient = User::factory()->create([
        'type' => RoleType::PATIENT,
    ]);

    $complain = Complain::factory()->create([
        'user_id' => $patient->id,
    ]);


    $result = $this->service->getClinicComplains($patient);


    $item = $result->first();


    expect($item)->not->toBeNull();


    expect($item->getAttributes())
        ->not->toHaveKey('some_hidden_column');

});
it('returns all complains for other roles', function () {

    $admin = User::factory()->create([
        'type' => RoleType::SUPER_ADMIN,
    ]);


    Complain::factory(5)->create();


    $result = $this->service->getClinicComplains($admin);


    expect($result)->toHaveCount(5);

});
it('returns empty collection if clinic has no owner match', function () {

    $clinicUser = User::factory()->create([
        'type' => RoleType::CLINIC,
    ]);


    Complain::factory()->create();


    $result = $this->service->getClinicComplains($clinicUser);


    expect($result)->toBeEmpty();

});