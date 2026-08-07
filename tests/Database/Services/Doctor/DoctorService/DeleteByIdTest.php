<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use App\Models\Speciality;
use App\Services\Doctor\DoctorService;
use Illuminate\Database\Eloquent\ModelNotFoundException;


it('removes doctor from the specified clinic and deletes clinic-specific service prices', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();

    $doctor->clinics()->attach($clinic->id);

    $doctor->specialities()->attach($speciality->id);

    $price1 = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
    ]);

    $price2 = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
    ]);

    $result = app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeTrue();

    // Doctor must still exist
    expect(
        Doctor::whereKey($doctor->id)->exists()
    )->toBeTrue();

    // Doctor must no longer belong to the clinic
    expect(
        DB::table('clinic_doctors')
            ->where('doctor_id', $doctor->id)
            ->where('clinic_id', $clinic->id)
            ->exists()
    )->toBeFalse();

    // Clinic itself must still exist
    expect(
        Clinic::whereKey($clinic->id)->exists()
    )->toBeTrue();

    // Speciality relation must still exist
    expect(
        DB::table('doctor_speciality')
            ->where('doctor_id', $doctor->id)
            ->where('speciality_id', $speciality->id)
            ->exists()
    )->toBeTrue();

    // Clinic-specific service prices must be deleted
    expect(
        Doctor_service_price::whereKey($price1->id)->exists()
    )->toBeFalse();

    expect(
        Doctor_service_price::whereKey($price2->id)->exists()
    )->toBeFalse();
});


it('removes doctor only from the specified clinic and keeps other clinic relations', function () {

    $doctor = Doctor::factory()->create();

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor->clinics()->attach([
        $clinic1->id,
        $clinic2->id,
    ]);

    $result = app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic1->id
    );

    expect($result)->toBeTrue();

    // Clinic 1 relation must be removed
    expect(
        DB::table('clinic_doctors')
            ->where('doctor_id', $doctor->id)
            ->where('clinic_id', $clinic1->id)
            ->exists()
    )->toBeFalse();

    // Clinic 2 relation must remain
    expect(
        DB::table('clinic_doctors')
            ->where('doctor_id', $doctor->id)
            ->where('clinic_id', $clinic2->id)
            ->exists()
    )->toBeTrue();

    // Doctor must still exist
    expect(
        Doctor::whereKey($doctor->id)->exists()
    )->toBeTrue();
});


it('deletes only service prices belonging to the specified clinic', function () {

    $doctor = Doctor::factory()->create();

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor->clinics()->attach([
        $clinic1->id,
        $clinic2->id,
    ]);

    $clinic1Price = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic1->id,
    ]);

    $clinic2Price = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic2->id,
    ]);

    app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic1->id
    );

    // Clinic 1 price must be deleted
    expect(
        Doctor_service_price::whereKey($clinic1Price->id)->exists()
    )->toBeFalse();

    // Clinic 2 price must remain
    expect(
        Doctor_service_price::whereKey($clinic2Price->id)->exists()
    )->toBeTrue();
});


it('keeps doctor specialities when removing doctor from a clinic', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $speciality1 = Speciality::factory()->create();
    $speciality2 = Speciality::factory()->create();

    $doctor->clinics()->attach($clinic->id);

    $doctor->specialities()->attach([
        $speciality1->id,
        $speciality2->id,
    ]);

    app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic->id
    );

    // Speciality records must remain
    expect(
        Speciality::whereKey($speciality1->id)->exists()
    )->toBeTrue();

    expect(
        Speciality::whereKey($speciality2->id)->exists()
    )->toBeTrue();

    // Doctor-speciality relations must remain
    expect(
        DB::table('doctor_speciality')
            ->where('doctor_id', $doctor->id)
            ->where('speciality_id', $speciality1->id)
            ->exists()
    )->toBeTrue();

    expect(
        DB::table('doctor_speciality')
            ->where('doctor_id', $doctor->id)
            ->where('speciality_id', $speciality2->id)
            ->exists()
    )->toBeTrue();
});


it('keeps service prices belonging to other clinics', function () {

    $doctor = Doctor::factory()->create();

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();
    $clinic3 = Clinic::factory()->create();

    $doctor->clinics()->attach([
        $clinic1->id,
        $clinic2->id,
        $clinic3->id,
    ]);

    $price1 = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic1->id,
    ]);

    $price2 = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic2->id,
    ]);

    $price3 = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic3->id,
    ]);

    app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic2->id
    );

    expect(
        Doctor_service_price::whereKey($price1->id)->exists()
    )->toBeTrue();

    expect(
        Doctor_service_price::whereKey($price2->id)->exists()
    )->toBeFalse();

    expect(
        Doctor_service_price::whereKey($price3->id)->exists()
    )->toBeTrue();
});


it('does not affect another doctors data', function () {

    $doctor1 = Doctor::factory()->create();
    $doctor2 = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $doctor1->clinics()->attach($clinic->id);
    $doctor2->clinics()->attach($clinic->id);

    $doctor1Price = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor1->id,
        'clinic_id' => $clinic->id,
    ]);

    $doctor2Price = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor2->id,
        'clinic_id' => $clinic->id,
    ]);

    app(DoctorService::class)->deleteById(
        $doctor1->id,
        $clinic->id
    );

    // Doctor 1 relation removed
    expect(
        DB::table('clinic_doctors')
            ->where('doctor_id', $doctor1->id)
            ->where('clinic_id', $clinic->id)
            ->exists()
    )->toBeFalse();

    // Doctor 2 relation remains
    expect(
        DB::table('clinic_doctors')
            ->where('doctor_id', $doctor2->id)
            ->where('clinic_id', $clinic->id)
            ->exists()
    )->toBeTrue();

    // Doctor 1 price removed
    expect(
        Doctor_service_price::whereKey($doctor1Price->id)->exists()
    )->toBeFalse();

    // Doctor 2 price remains
    expect(
        Doctor_service_price::whereKey($doctor2Price->id)->exists()
    )->toBeTrue();

    // Both doctors still exist
    expect(
        Doctor::whereKey($doctor1->id)->exists()
    )->toBeTrue();

    expect(
        Doctor::whereKey($doctor2->id)->exists()
    )->toBeTrue();
});


it('keeps doctor when he is removed from his only clinic', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $doctor->clinics()->attach($clinic->id);

    $price = Doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'clinic_id' => $clinic->id,
    ]);

    app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic->id
    );

    // Doctor must NOT be deleted
    expect(
        Doctor::whereKey($doctor->id)->exists()
    )->toBeTrue();

    // Clinic relation removed
    expect(
        DB::table('clinic_doctors')
            ->where('doctor_id', $doctor->id)
            ->where('clinic_id', $clinic->id)
            ->exists()
    )->toBeFalse();

    // Clinic-specific price removed
    expect(
        Doctor_service_price::whereKey($price->id)->exists()
    )->toBeFalse();
});


it('succeeds when doctor has no service prices in the specified clinic', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $doctor->clinics()->attach($clinic->id);

    $result = app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeTrue();

    expect(
        Doctor::whereKey($doctor->id)->exists()
    )->toBeTrue();

    expect(
        DB::table('clinic_doctors')
            ->where('doctor_id', $doctor->id)
            ->where('clinic_id', $clinic->id)
            ->exists()
    )->toBeFalse();
});


it('succeeds when doctor has no specialities', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $doctor->clinics()->attach($clinic->id);

    $result = app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeTrue();

    expect(
        Doctor::whereKey($doctor->id)->exists()
    )->toBeTrue();

    expect(
        DB::table('clinic_doctors')
            ->where('doctor_id', $doctor->id)
            ->where('clinic_id', $clinic->id)
            ->exists()
    )->toBeFalse();
});


it('succeeds when doctor is not attached to the specified clinic', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $result = app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeTrue();

    expect(
        Doctor::whereKey($doctor->id)->exists()
    )->toBeTrue();

    expect(
        Clinic::whereKey($clinic->id)->exists()
    )->toBeTrue();
});


it('throws ModelNotFoundException when doctor does not exist', function () {

    $clinic = Clinic::factory()->create();

    app(DoctorService::class)->deleteById(
        999999999,
        $clinic->id
    );

})->throws(ModelNotFoundException::class);


it('does not delete clinic when removing doctor from clinic', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $doctor->clinics()->attach($clinic->id);

    app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic->id
    );

    expect(
        Clinic::whereKey($clinic->id)->exists()
    )->toBeTrue();
});


it('returns true when doctor is successfully removed from clinic', function () {

    $doctor = Doctor::factory()->create();

    $clinic = Clinic::factory()->create();

    $doctor->clinics()->attach($clinic->id);

    $result = app(DoctorService::class)->deleteById(
        $doctor->id,
        $clinic->id
    );

    expect($result)->toBeTrue();
});