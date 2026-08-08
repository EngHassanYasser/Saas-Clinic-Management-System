<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Services\Doctor\DoctorStatisticsService;


it('returns correct doctor statistics for a clinic', function () {

    $clinic = Clinic::factory()->create();

    $speciality1 = Speciality::factory()->create();
    $speciality2 = Speciality::factory()->create();

    $doctor1 = Doctor::factory()->create();
    $doctor2 = Doctor::factory()->create();
    $doctor3 = Doctor::factory()->create();

    $doctor1->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor2->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor3->clinics()->attach($clinic->id, [
        'is_active' => false,
    ]);

    $doctor1->specialities()->attach([
        $speciality1->id,
        $speciality2->id,
    ]);

    $doctor2->specialities()->attach($speciality1->id);

    $doctor3->specialities()->attach($speciality2->id);

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(3)
        ->and($stats['active'])
        ->toBe(2)
        ->and($stats['inactive'])
        ->toBe(1)
        ->and($stats['specialities'])
        ->toBe(2);
});


it('counts only doctors belonging to the requested clinic', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $doctor1 = Doctor::factory()->create();
    $doctor2 = Doctor::factory()->create();
    $doctor3 = Doctor::factory()->create();

    $doctor1->clinics()->attach($clinic1->id, [
        'is_active' => true,
    ]);

    $doctor2->clinics()->attach($clinic1->id, [
        'is_active' => false,
    ]);

    $doctor3->clinics()->attach($clinic2->id, [
        'is_active' => true,
    ]);

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic1->id);

    expect($stats['total'])
        ->toBe(2)
        ->and($stats['active'])
        ->toBe(1)
        ->and($stats['inactive'])
        ->toBe(1);
});


it('does not count doctors from another clinic in speciality statistics', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $speciality1 = Speciality::factory()->create();
    $speciality2 = Speciality::factory()->create();

    $doctor1 = Doctor::factory()->create();
    $doctor2 = Doctor::factory()->create();

    $doctor1->clinics()->attach($clinic1->id, [
        'is_active' => true,
    ]);

    $doctor2->clinics()->attach($clinic2->id, [
        'is_active' => true,
    ]);

    $doctor1->specialities()->attach($speciality1->id);
    $doctor2->specialities()->attach($speciality2->id);

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic1->id);

    expect($stats['total'])
        ->toBe(1)
        ->and($stats['active'])
        ->toBe(1)
        ->and($stats['inactive'])
        ->toBe(0)
        ->and($stats['specialities'])
        ->toBe(1);
});


it('counts distinct specialities only once', function () {

    $clinic = Clinic::factory()->create();

    $speciality1 = Speciality::factory()->create();
    $speciality2 = Speciality::factory()->create();

    $doctor1 = Doctor::factory()->create();
    $doctor2 = Doctor::factory()->create();
    $doctor3 = Doctor::factory()->create();

    $doctor1->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor2->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor3->clinics()->attach($clinic->id, [
        'is_active' => false,
    ]);

    // speciality1 is shared by all three doctors
    $doctor1->specialities()->attach([
        $speciality1->id,
        $speciality2->id,
    ]);

    $doctor2->specialities()->attach($speciality1->id);

    $doctor3->specialities()->attach($speciality1->id);

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(3)
        ->and($stats['active'])
        ->toBe(2)
        ->and($stats['inactive'])
        ->toBe(1)
        ->and($stats['specialities'])
        ->toBe(2);
});


it('does not multiply doctor counts when a doctor has multiple specialities', function () {

    $clinic = Clinic::factory()->create();

    $speciality1 = Speciality::factory()->create();
    $speciality2 = Speciality::factory()->create();
    $speciality3 = Speciality::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor->specialities()->attach([
        $speciality1->id,
        $speciality2->id,
        $speciality3->id,
    ]);

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(1)
        ->and($stats['active'])
        ->toBe(1)
        ->and($stats['inactive'])
        ->toBe(0)
        ->and($stats['specialities'])
        ->toBe(3);
});


it('does not multiply inactive doctors because of multiple specialities', function () {

    $clinic = Clinic::factory()->create();

    $speciality1 = Speciality::factory()->create();
    $speciality2 = Speciality::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => false,
    ]);

    $doctor->specialities()->attach([
        $speciality1->id,
        $speciality2->id,
    ]);

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(1)
        ->and($stats['active'])
        ->toBe(0)
        ->and($stats['inactive'])
        ->toBe(1)
        ->and($stats['specialities'])
        ->toBe(2);
});


it('counts active and inactive doctors independently', function () {

    $clinic = Clinic::factory()->create();

    $activeDoctors = Doctor::factory()->count(5)->create();
    $inactiveDoctors = Doctor::factory()->count(3)->create();

    foreach ($activeDoctors as $doctor) {
        $doctor->clinics()->attach($clinic->id, [
            'is_active' => true,
        ]);
    }

    foreach ($inactiveDoctors as $doctor) {
        $doctor->clinics()->attach($clinic->id, [
            'is_active' => false,
        ]);
    }

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(8)
        ->and($stats['active'])
        ->toBe(5)
        ->and($stats['inactive'])
        ->toBe(3);
});


it('returns zero statistics when clinic has no doctors', function () {

    $clinic = Clinic::factory()->create();

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(0)
        ->and($stats['active'])
        ->toBe(0)
        ->and($stats['inactive'])
        ->toBe(0)
        ->and($stats['specialities'])
        ->toBe(0);
});


it('returns zero statistics when clinic does not exist', function () {

    $stats = app(DoctorStatisticsService::class)
        ->getStats(999999999);

    expect($stats['total'])
        ->toBe(0)
        ->and($stats['active'])
        ->toBe(0)
        ->and($stats['inactive'])
        ->toBe(0)
        ->and($stats['specialities'])
        ->toBe(0);
});


it('returns zero specialities when clinic has doctors without specialities', function () {

    $clinic = Clinic::factory()->create();

    $doctors = Doctor::factory()->count(4)->create();

    foreach ($doctors as $doctor) {
        $doctor->clinics()->attach($clinic->id, [
            'is_active' => true,
        ]);
    }

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(4)
        ->and($stats['active'])
        ->toBe(4)
        ->and($stats['inactive'])
        ->toBe(0)
        ->and($stats['specialities'])
        ->toBe(0);
});


it('returns zero active doctors when all doctors are inactive', function () {

    $clinic = Clinic::factory()->create();

    $doctors = Doctor::factory()->count(4)->create();

    foreach ($doctors as $doctor) {
        $doctor->clinics()->attach($clinic->id, [
            'is_active' => false,
        ]);
    }

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(4)
        ->and($stats['active'])
        ->toBe(0)
        ->and($stats['inactive'])
        ->toBe(4);
});


it('returns zero inactive doctors when all doctors are active', function () {

    $clinic = Clinic::factory()->create();

    $doctors = Doctor::factory()->count(4)->create();

    foreach ($doctors as $doctor) {
        $doctor->clinics()->attach($clinic->id, [
            'is_active' => true,
        ]);
    }

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(4)
        ->and($stats['active'])
        ->toBe(4)
        ->and($stats['inactive'])
        ->toBe(0);
});


it('keeps statistics isolated between clinics', function () {

    $clinic1 = Clinic::factory()->create();
    $clinic2 = Clinic::factory()->create();

    $speciality1 = Speciality::factory()->create();
    $speciality2 = Speciality::factory()->create();

    $doctor1 = Doctor::factory()->create();
    $doctor2 = Doctor::factory()->create();
    $doctor3 = Doctor::factory()->create();

    $doctor1->clinics()->attach($clinic1->id, [
        'is_active' => true,
    ]);

    $doctor2->clinics()->attach($clinic1->id, [
        'is_active' => false,
    ]);

    $doctor3->clinics()->attach($clinic2->id, [
        'is_active' => true,
    ]);

    $doctor1->specialities()->attach($speciality1->id);
    $doctor2->specialities()->attach($speciality2->id);
    $doctor3->specialities()->attach($speciality1->id);

    $clinic1Stats = app(DoctorStatisticsService::class)
        ->getStats($clinic1->id);

    $clinic2Stats = app(DoctorStatisticsService::class)
        ->getStats($clinic2->id);

    expect($clinic1Stats['total'])
        ->toBe(2)
        ->and($clinic1Stats['active'])
        ->toBe(1)
        ->and($clinic1Stats['inactive'])
        ->toBe(1)
        ->and($clinic1Stats['specialities'])
        ->toBe(2);

    expect($clinic2Stats['total'])
        ->toBe(1)
        ->and($clinic2Stats['active'])
        ->toBe(1)
        ->and($clinic2Stats['inactive'])
        ->toBe(0)
        ->and($clinic2Stats['specialities'])
        ->toBe(1);
});


it('counts speciality only once even when shared by many doctors in the same clinic', function () {

    $clinic = Clinic::factory()->create();

    $speciality = Speciality::factory()->create();

    $doctors = Doctor::factory()->count(5)->create();

    foreach ($doctors as $doctor) {

        $doctor->clinics()->attach($clinic->id, [
            'is_active' => true,
        ]);

        $doctor->specialities()->attach($speciality->id);
    }

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(5)
        ->and($stats['active'])
        ->toBe(5)
        ->and($stats['inactive'])
        ->toBe(0)
        ->and($stats['specialities'])
        ->toBe(1);
});


it('returns integer values for all statistics', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $speciality = Speciality::factory()->create();

    $doctor->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor->specialities()->attach($speciality->id);

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])->toBeInt()
        ->and($stats['active'])->toBeInt()
        ->and($stats['inactive'])->toBeInt()
        ->and($stats['specialities'])->toBeInt();
});


it('does not count speciality belonging to a doctor outside the clinic', function () {

    $clinic = Clinic::factory()->create();

    $otherClinic = Clinic::factory()->create();

    $clinicDoctor = Doctor::factory()->create();
    $otherClinicDoctor = Doctor::factory()->create();

    $clinicSpeciality = Speciality::factory()->create();
    $otherSpeciality = Speciality::factory()->create();

    $clinicDoctor->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $otherClinicDoctor->clinics()->attach($otherClinic->id, [
        'is_active' => true,
    ]);

    $clinicDoctor->specialities()->attach($clinicSpeciality->id);
    $otherClinicDoctor->specialities()->attach($otherSpeciality->id);

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(1)
        ->and($stats['active'])
        ->toBe(1)
        ->and($stats['inactive'])
        ->toBe(0)
        ->and($stats['specialities'])
        ->toBe(1);
});


it('handles doctors with no speciality alongside doctors with specialities', function () {

    $clinic = Clinic::factory()->create();

    $speciality1 = Speciality::factory()->create();
    $speciality2 = Speciality::factory()->create();

    $doctorWithoutSpeciality = Doctor::factory()->create();
    $doctorWithSpeciality = Doctor::factory()->create();

    $doctorWithoutSpeciality->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctorWithSpeciality->clinics()->attach($clinic->id, [
        'is_active' => false,
    ]);

    $doctorWithSpeciality->specialities()->attach([
        $speciality1->id,
        $speciality2->id,
    ]);

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(2)
        ->and($stats['active'])
        ->toBe(1)
        ->and($stats['inactive'])
        ->toBe(1)
        ->and($stats['specialities'])
        ->toBe(2);
});


it('handles a doctor having many specialities while other doctors have none', function () {

    $clinic = Clinic::factory()->create();

    $specialities = Speciality::factory()->count(6)->create();

    $doctor1 = Doctor::factory()->create();
    $doctor2 = Doctor::factory()->create();
    $doctor3 = Doctor::factory()->create();

    $doctor1->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor2->clinics()->attach($clinic->id, [
        'is_active' => true,
    ]);

    $doctor3->clinics()->attach($clinic->id, [
        'is_active' => false,
    ]);

    $doctor1->specialities()->attach(
        $specialities->pluck('id')->all()
    );

    $stats = app(DoctorStatisticsService::class)
        ->getStats($clinic->id);

    expect($stats['total'])
        ->toBe(3)
        ->and($stats['active'])
        ->toBe(2)
        ->and($stats['inactive'])
        ->toBe(1)
        ->and($stats['specialities'])
        ->toBe(6);
});
