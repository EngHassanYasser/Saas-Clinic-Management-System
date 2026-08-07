<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Services\Doctor\DoctorQueryService;
use Illuminate\Database\Eloquent\Collection;

beforeEach(function () {
    $this->service = app(DoctorQueryService::class);
});


it('returns doctors belonging to the requested clinic', function () {
    $clinic = Clinic::factory()->create();

    $doctors = Doctor::factory()
        ->count(3)
        ->create();

    $clinic->doctors()->attach($doctors->pluck('id'));

    $result = $this->service->getDoctorsNames($clinic->id);

    expect($result)->toHaveCount(3)
        ->and($result->pluck('id')->sort()->values()->all())
        ->toBe($doctors->pluck('id')->sort()->values()->all());
});


it('does not return doctors belonging to another clinic', function () {
    $requestedClinic = Clinic::factory()->create();
    $otherClinic = Clinic::factory()->create();

    $requestedDoctors = Doctor::factory()
        ->count(3)
        ->create();

    $otherDoctors = Doctor::factory()
        ->count(2)
        ->create();

    $requestedClinic->doctors()->attach(
        $requestedDoctors->pluck('id')
    );

    $otherClinic->doctors()->attach(
        $otherDoctors->pluck('id')
    );

    $result = $this->service->getDoctorsNames($requestedClinic->id);

    expect($result)->toHaveCount(3)
        ->and($result->pluck('id')->sort()->values()->all())
        ->toBe($requestedDoctors->pluck('id')->sort()->values()->all())
        ->and($result->pluck('id')->all())
        ->not->toContain($otherDoctors->first()->id);
});


it('returns an empty collection when the clinic has no doctors', function () {
    $clinic = Clinic::factory()->create();

    $result = $this->service->getDoctorsNames($clinic->id);

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});


it('returns an empty collection when the clinic does not exist', function () {
    $nonExistingClinicId = Clinic::max('id') + 1;

    $result = $this->service->getDoctorsNames($nonExistingClinicId);

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});


it('does not return doctors that are not attached to any clinic', function () {
    $clinic = Clinic::factory()->create();

    $attachedDoctors = Doctor::factory()
        ->count(2)
        ->create();

    Doctor::factory()
        ->count(3)
        ->create();

    $clinic->doctors()->attach(
        $attachedDoctors->pluck('id')
    );

    $result = $this->service->getDoctorsNames($clinic->id);

    expect($result)->toHaveCount(2)
        ->and($result->pluck('id')->sort()->values()->all())
        ->toBe($attachedDoctors->pluck('id')->sort()->values()->all());
});


it('returns a doctor when the doctor belongs to multiple clinics', function () {
    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinicOne->doctors()->attach($doctor->id);
    $clinicTwo->doctors()->attach($doctor->id);

    $resultOne = $this->service->getDoctorsNames($clinicOne->id);
    $resultTwo = $this->service->getDoctorsNames($clinicTwo->id);

    expect($resultOne)->toHaveCount(1)
        ->and($resultOne->first()->id)->toBe($doctor->id)
        ->and($resultTwo)->toHaveCount(1)
        ->and($resultTwo->first()->id)->toBe($doctor->id);
});


it('returns only id and name columns', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create([
        'name' => 'Dr. Ahmed',
    ]);

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getDoctorsNames($clinic->id);

    $returnedDoctor = $result->first();

    expect($returnedDoctor->getAttributes())
        ->toHaveKeys(['id', 'name'])
        ->not->toHaveKey('phone')
        ->not->toHaveKey('email');
});


it('returns the correct doctor names', function () {
    $clinic = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create([
        'name' => 'Dr. Ahmed',
    ]);

    $doctorTwo = Doctor::factory()->create([
        'name' => 'Dr. Mohamed',
    ]);

    $clinic->doctors()->attach([
        $doctorOne->id,
        $doctorTwo->id,
    ]);

    $result = $this->service->getDoctorsNames($clinic->id);

    expect($result->pluck('name')->sort()->values()->all())
        ->toBe([
            'Dr. Ahmed',
            'Dr. Mohamed',
        ]);
});


it('returns the correct number of doctors', function () {
    $clinic = Clinic::factory()->create();

    $doctors = Doctor::factory()
        ->count(7)
        ->create();

    $clinic->doctors()->attach(
        $doctors->pluck('id')
    );

    $result = $this->service->getDoctorsNames($clinic->id);

    expect($result)->toHaveCount(7);
});


it('does not include doctors from other clinics even when they have similar names', function () {
    $requestedClinic = Clinic::factory()->create();
    $otherClinic = Clinic::factory()->create();

    $requestedDoctor = Doctor::factory()->create([
        'name' => 'Dr. Ahmed',
    ]);

    $otherDoctor = Doctor::factory()->create([
        'name' => 'Dr. Ahmed',
    ]);

    $requestedClinic->doctors()->attach($requestedDoctor->id);
    $otherClinic->doctors()->attach($otherDoctor->id);

    $result = $this->service->getDoctorsNames($requestedClinic->id);

    expect($result)->toHaveCount(1)
        ->and($result->first()->id)->toBe($requestedDoctor->id);
});


it('does not modify the database', function () {
    $clinic = Clinic::factory()->create();

    $doctors = Doctor::factory()
        ->count(3)
        ->create();

    $clinic->doctors()->attach(
        $doctors->pluck('id')
    );

    $doctorsBefore = Doctor::count();
    $clinicsBefore = Clinic::count();

    $this->service->getDoctorsNames($clinic->id);

    expect(Doctor::count())
        ->toBe($doctorsBefore)
        ->and(Clinic::count())
        ->toBe($clinicsBefore);
});


it('returns an eloquent collection', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getDoctorsNames($clinic->id);

    expect($result)
        ->toBeInstanceOf(Collection::class);
});


it('returns doctors only from the requested clinic when doctors belong to multiple clinics', function () {
    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();
    $clinicThree = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create();
    $doctorTwo = Doctor::factory()->create();
    $doctorThree = Doctor::factory()->create();

    $clinicOne->doctors()->attach([
        $doctorOne->id,
        $doctorTwo->id,
    ]);

    $clinicTwo->doctors()->attach([
        $doctorTwo->id,
        $doctorThree->id,
    ]);

    $clinicThree->doctors()->attach([
        $doctorThree->id,
    ]);

    $result = $this->service->getDoctorsNames($clinicTwo->id);

    expect($result->pluck('id')->sort()->values()->all())
        ->toBe([
            $doctorTwo->id,
            $doctorThree->id,
        ]);
});


it('returns the same doctor only once', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getDoctorsNames($clinic->id);

    expect($result->where('id', $doctor->id))->toHaveCount(1);
});


it('returns an empty collection when there are no doctors in the database', function () {
    $clinic = Clinic::factory()->create();

    $result = $this->service->getDoctorsNames($clinic->id);

    expect($result)->toBeEmpty();
});


it('does not depend on doctor relationships being eager loaded', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create([
        'name' => 'Dr. Test',
    ]);

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getDoctorsNames($clinic->id);

    expect($result->first()->id)
        ->toBe($doctor->id)
        ->and($result->first()->name)
        ->toBe('Dr. Test');
});