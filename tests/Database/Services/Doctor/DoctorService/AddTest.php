<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Services\Doctor\DoctorService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->service = app(DoctorService::class);
});

it('creates a doctor with the provided data', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Ahmed Ali',
        'phone' => '01012345678',
        'email' => 'ahmed@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect($doctor)
        ->toBeInstanceOf(Doctor::class)
        ->and($doctor->name)->toBe('Dr. Ahmed Ali')
        ->and($doctor->phone)->toBe('01012345678')
        ->and($doctor->email)->toBe('ahmed@example.com');

    $this->assertDatabaseHas('doctors', [
        'id' => $doctor->id,
        'name' => 'Dr. Ahmed Ali',
        'phone' => '01012345678',
        'email' => 'ahmed@example.com',
    ]);
});

it('returns the newly created doctor', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Mohamed Hassan',
        'phone' => '01111111111',
        'email' => 'mohamed@example.com',
        'speciality_id' => $speciality->id,
    ];

    $result = $this->service->add($data, $clinic->id);

    expect($result)
        ->toBeInstanceOf(Doctor::class)
        ->and($result->exists)->toBeTrue()
        ->and($result->id)->toBeInt();
});

it('attaches the doctor to the requested speciality', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000001',
        'email' => 'ahmed@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect(
        $doctor->specialities()
            ->whereKey($speciality->id)
            ->exists()
    )->toBeTrue();

    $this->assertDatabaseHas('doctor_speciality', [
        'doctor_id' => $doctor->id,
        'speciality_id' => $speciality->id,
    ]);
});

it('attaches the doctor only to the requested speciality', function () {

    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();
    $otherSpeciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000002',
        'email' => 'ahmed2@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect($doctor->specialities()->count())->toBe(1)
        ->and($doctor->specialities()->first()->id)
        ->toBe($speciality->id)
        ->and(
            $doctor->specialities()
                ->whereKey($otherSpeciality->id)
                ->exists()
        )->toBeFalse();
});

it('attaches the doctor to the requested clinic', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000003',
        'email' => 'ahmed3@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect(
        $doctor->clinics()
            ->whereKey($clinic->id)
            ->exists()
    )->toBeTrue();

    $this->assertDatabaseHas('clinic_doctors', [
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
    ]);
});

it('does not attach the doctor to another clinic', function () {

    $clinic = Clinic::factory()->create();
    $otherClinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000004',
        'email' => 'ahmed4@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect($doctor->clinics()->count())->toBe(1)
        ->and(
            $doctor->clinics()
                ->whereKey($clinic->id)
                ->exists()
        )->toBeTrue()
        ->and(
            $doctor->clinics()
                ->whereKey($otherClinic->id)
                ->exists()
        )->toBeFalse();
});

it('does not modify the provided input data', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000005',
        'email' => 'ahmed5@example.com',
        'speciality_id' => $speciality->id,
    ];

    $originalData = $data;

    $this->service->add($data, $clinic->id);

    expect($data)->toBe($originalData);
});

it('does not create an avatar when no image is provided', function () {

    Storage::fake();

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000006',
        'email' => 'ahmed6@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect($doctor->getMedia('avatar'))->toHaveCount(0);
});

it('stores the provided image in the avatar media collection', function () {

    Storage::fake();

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $image = UploadedFile::fake()->image('doctor.jpg');

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000007',
        'email' => 'ahmed7@example.com',
        'speciality_id' => $speciality->id,
        'image' => $image,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect($doctor->getMedia('avatar'))
        ->toHaveCount(1);

    $media = $doctor->getFirstMedia('avatar');

    expect($media)->not->toBeNull()
        ->and($media->collection_name)->toBe('avatar');
});

it('creates exactly one doctor', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $doctorsBefore = Doctor::count();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000008',
        'email' => 'ahmed8@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect(Doctor::count())
        ->toBe($doctorsBefore + 1);

    expect(Doctor::whereKey($doctor->id)->count())
        ->toBe(1);
});

it('creates exactly one speciality pivot record', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $before = DB::table('doctor_speciality')->count();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000009',
        'email' => 'ahmed9@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect(DB::table('doctor_speciality')->count())
        ->toBe($before + 1);

    $this->assertDatabaseHas('doctor_speciality', [
        'doctor_id' => $doctor->id,
        'speciality_id' => $speciality->id,
    ]);
});

it('creates exactly one clinic pivot record', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $before = DB::table('clinic_doctors')->count();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01000000010',
        'email' => 'ahmed10@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect(DB::table('clinic_doctors')->count())
        ->toBe($before + 1);

    $this->assertDatabaseHas('clinic_doctors', [
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
    ]);
});

it('works with different clinics and specialities independently', function () {

    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();

    $specialityOne = Speciality::factory()->create();
    $specialityTwo = Speciality::factory()->create();

    $doctorOne = $this->service->add([
        'name' => 'Doctor One',
        'phone' => '01011111111',
        'email' => 'doctor1@example.com',
        'speciality_id' => $specialityOne->id,
    ], $clinicOne->id);

    $doctorTwo = $this->service->add([
        'name' => 'Doctor Two',
        'phone' => '01022222222',
        'email' => 'doctor2@example.com',
        'speciality_id' => $specialityTwo->id,
    ], $clinicTwo->id);

    expect($doctorOne->clinics()->pluck('clinics.id')->all())
        ->toBe([$clinicOne->id]);

    expect($doctorTwo->clinics()->pluck('clinics.id')->all())
        ->toBe([$clinicTwo->id]);

    expect($doctorOne->specialities()->pluck('specialities.id')->all())
        ->toBe([$specialityOne->id]);

    expect($doctorTwo->specialities()->pluck('specialities.id')->all())
        ->toBe([$specialityTwo->id]);
});

it('does not modify existing doctors when creating a new doctor', function () {

    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();

    $existingDoctor = Doctor::factory()->create([
        'name' => 'Existing Doctor',
    ]);

    $existingDoctor->clinics()->attach($clinic->id);

    $existingDoctor->specialities()->attach($speciality->id);

    $data = [
        'name' => 'New Doctor',
        'phone' => '01033333333',
        'email' => 'newdoctor@example.com',
        'speciality_id' => $speciality->id,
    ];

    $newDoctor = $this->service->add($data, $clinic->id);

    $existingDoctor->refresh();

    expect(Doctor::count())->toBe(2)
        ->and($existingDoctor->name)->toBe('Existing Doctor')
        ->and($newDoctor->id)->not->toBe($existingDoctor->id);
});

it('handles a nullable image correctly', function () {

    Storage::fake();

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01044444444',
        'email' => 'ahmed11@example.com',
        'speciality_id' => $speciality->id,
        'image' => null,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect($doctor)
        ->toBeInstanceOf(Doctor::class)
        ->and($doctor->getMedia('avatar'))->toHaveCount(0);
});

it('does not create duplicate clinic or speciality pivots during a single add operation', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01055555555',
        'email' => 'ahmed12@example.com',
        'speciality_id' => $speciality->id,
    ];

    $doctor = $this->service->add($data, $clinic->id);

    expect(
        DB::table('clinic_doctors')
            ->where('doctor_id', $doctor->id)
            ->where('clinic_id', $clinic->id)
            ->count()
    )->toBe(1);

    expect(
        DB::table('doctor_speciality')
            ->where('doctor_id', $doctor->id)
            ->where('speciality_id', $speciality->id)
            ->count()
    )->toBe(1);
});

it('does not write unrelated data', function () {

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $doctorsBefore = Doctor::count();
    $clinicsBefore = Clinic::count();
    $specialitiesBefore = Speciality::count();

    $data = [
        'name' => 'Dr. Ahmed',
        'phone' => '01066666666',
        'email' => 'ahmed13@example.com',
        'speciality_id' => $speciality->id,
    ];

    $this->service->add($data, $clinic->id);

    expect(Clinic::count())->toBe($clinicsBefore)
        ->and(Speciality::count())->toBe($specialitiesBefore)
        ->and(Doctor::count())->toBe($doctorsBefore + 1);
});
