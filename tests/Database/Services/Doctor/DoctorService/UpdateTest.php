<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Services\Doctor\DoctorService;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;
it('updates doctor data, speciality, clinic pivot and avatar successfully', function () {

    $doctor = Doctor::factory()->create([
        'name' => 'Old Name',
        'phone' => '01000000000',
        'email' => 'old@example.com',
    ]);

    $oldSpeciality = Speciality::factory()->create();
    $newSpeciality = Speciality::factory()->create();

    $clinic = Clinic::factory()->create();

    $doctor->specialities()->attach($oldSpeciality->id);

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => false,
    ]);

    $image = UploadedFile::fake()->image('doctor.jpg');

    $data = [
        'name' => 'New Name',
        'phone' => '01111111111',
        'email' => 'new@example.com',
        'speciality_id' => $newSpeciality->id,
        'is_active' => true,
        'image' => $image,
    ];

    $service = app(DoctorService::class);

    $result = $service->update(
        $data,
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeTrue();

    $doctor->refresh();

    expect($doctor->name)
        ->toBe('New Name')
        ->and($doctor->phone)
        ->toBe('01111111111')
        ->and($doctor->email)
        ->toBe('new@example.com');

    expect($doctor->specialities)
        ->toHaveCount(1)
        ->and($doctor->specialities->first()->id)
        ->toBe($newSpeciality->id);

    expect(
        $doctor->clinics()
            ->where('clinic_id', $clinic->id)
            ->first()
            ->pivot
            ->is_active
    )->toBe(1);

    expect($doctor->getMedia('avatar'))
        ->toHaveCount(1);
});
it('replaces doctor specialities using sync', function () {

    $doctor = Doctor::factory()->create();

    $oldSpeciality = Speciality::factory()->create();
    $newSpeciality = Speciality::factory()->create();

    $clinic = Clinic::factory()->create();

    $doctor->specialities()->attach($oldSpeciality->id);

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $data = [
        'name' => $doctor->name,
        'phone' => $doctor->phone,
        'email' => $doctor->email,
        'speciality_id' => $newSpeciality->id,
        'is_active' => false,
    ];

    app(DoctorService::class)->update(
        $data,
        $doctor->id,
        $clinic->id
    );

    expect($doctor->specialities()->pluck('specialities.id')->all())
        ->toBe([$newSpeciality->id]);

    expect($doctor->specialities()->whereKey($oldSpeciality->id)->exists())
        ->toBeFalse();
});
it('does not create duplicate speciality relation', function () {

    $doctor = Doctor::factory()->create();

    $speciality = Speciality::factory()->create();
    $clinic = Clinic::factory()->create();

    $doctor->specialities()->attach($speciality->id);

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $data = [
        'name' => $doctor->name,
        'phone' => $doctor->phone,
        'email' => $doctor->email,
        'speciality_id' => $speciality->id,
        'is_active' => false,
    ];

    app(DoctorService::class)->update(
        $data,
        $doctor->id,
        $clinic->id
    );

    expect(
        $doctor->specialities()
            ->whereKey($speciality->id)
            ->count()
    )->toBe(1);
});
it('updates only the specified clinic pivot', function () {

    $doctor = Doctor::factory()->create();

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor->clinics()->attach($clinic1->id, [
        'is_active' => false,
    ]);

    $doctor->clinics()->attach($clinic2->id, [
        'is_active' => false,
    ]);

    $speciality = Speciality::factory()->create();

    $doctor->specialities()->attach($speciality->id);

    $data = [
        'name'          => $doctor->name,
        'phone'         => $doctor->phone,
        'email'         => $doctor->email,
        'speciality_id' => $speciality->id,
        'is_active'     => true,
    ];

    app(DoctorService::class)->update(
        $data,
        $doctor->id,
        $clinic1->id
    );

    expect(
        $doctor->clinics()
            ->whereKey($clinic1->id)
            ->first()
            ->pivot
            ->is_active
    )->toBe(1);

    expect(
        $doctor->clinics()
            ->whereKey($clinic2->id)
            ->first()
            ->pivot
            ->is_active
    )->toBe(0);
});
it('can deactivate doctor from the clinic', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor->specialities()->attach($speciality->id);

    $data = [
        'name'          => $doctor->name,
        'phone'         => $doctor->phone,
        'email'         => $doctor->email,
        'speciality_id' => $speciality->id,
        'is_active'     => false,
    ];

    app(DoctorService::class)->update(
        $data,
        $doctor->id,
        $clinic->id
    );

    expect(
        $doctor->clinics()
            ->whereKey($clinic->id)
            ->first()
            ->pivot
            ->is_active
    )->toBe(0);
});
it('replaces the existing avatar when a new image is provided', function () {

    Storage::fake('public');

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor->specialities()->attach($speciality->id);

    $oldImage = UploadedFile::fake()->image('old.jpg');

    $doctor->addMedia($oldImage)
        ->toMediaCollection('avatar');

    expect($doctor->getMedia('avatar'))->toHaveCount(1);

    $newImage = UploadedFile::fake()->image('new.jpg');

    $data = [
        'name'          => $doctor->name,
        'phone'         => $doctor->phone,
        'email'         => $doctor->email,
        'speciality_id' => $speciality->id,
        'is_active'     => true,
        'image'         => $newImage,
    ];

    app(DoctorService::class)->update(
        $data,
        $doctor->id,
        $clinic->id
    );

    $media = $doctor->fresh()->getMedia('avatar');

    expect($media)
        ->toHaveCount(1)
        ->and($media->first()->file_name)
        ->toContain('new');
});

it('keeps the existing avatar when no new image is provided', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();
    $speciality = Speciality::factory()->create();

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor->specialities()->attach($speciality->id);

    $oldImage = UploadedFile::fake()->image('old.jpg');

    $doctor->addMedia($oldImage)
        ->toMediaCollection('avatar');

    $before = $doctor->getMedia('avatar')->first();

    $data = [
        'name'          => 'Updated Doctor',
        'phone'         => '01111111111',
        'email'         => 'updated@example.com',
        'speciality_id' => $speciality->id,
        'is_active'     => false,
    ];

    app(DoctorService::class)->update(
        $data,
        $doctor->id,
        $clinic->id
    );

    $after = $doctor->fresh()->getMedia('avatar')->first();

    expect($after->id)->toBe($before->id);
});
it('throws model not found exception when doctor does not exist', function () {

    $clinic = Clinic::factory()->create();

    $data = [
        'name'          => 'Doctor',
        'phone'         => '01111111111',
        'email'         => 'doctor@example.com',
        'speciality_id' => Speciality::factory()->create()->id,
        'is_active'     => true,
    ];

    app(DoctorService::class)->update(
        $data,
        999999,
        $clinic->id
    );

})->throws(ModelNotFoundException::class);
it('rolls back doctor update when a database operation fails', function () {

    $doctor = Doctor::factory()->create([
        'name'  => 'Old Name',
        'phone' => '01000000000',
        'email' => 'old@example.com',
    ]);

    $clinic = Clinic::factory()->create();

    $oldSpeciality = Speciality::factory()->create();

    $doctor->specialities()->attach($oldSpeciality->id);

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => false,
    ]);

    $data = [
        'name'          => 'New Name',
        'phone'         => '01111111111',
        'email'         => 'new@example.com',

        // ID غير موجود
        'speciality_id' => 999999999,

        'is_active'     => true,
    ];

    expect(fn () =>
        app(DoctorService::class)->update(
            $data,
            $doctor->id,
            $clinic->id
        )
    )->toThrow(QueryException::class);

    $doctor->refresh();

    expect($doctor->name)
        ->toBe('Old Name')
        ->and($doctor->phone)
        ->toBe('01000000000')
        ->and($doctor->email)
        ->toBe('old@example.com');

    expect($doctor->specialities()->pluck('specialities.id')->all())
        ->toBe([$oldSpeciality->id]);

    expect(
        $doctor->clinics()
            ->whereKey($clinic->id)
            ->first()
            ->pivot
            ->is_active
    )->toBe(0);
});



