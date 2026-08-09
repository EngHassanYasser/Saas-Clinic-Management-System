<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Speciality;
use App\Models\DoctorService;
use App\Models\Schedule;
use App\Models\doctor_service_price;
use App\Models\User;
use App\Services\Doctor\DoctorQueryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(DoctorQueryService::class);
});


it('returns all doctors belonging to the clinic', function () {
    $clinic = Clinic::factory()->create();

    $doctors = Doctor::factory()
        ->count(3)
        ->create();

    $clinic->doctors()->attach($doctors->pluck('id'));

    $result = $this->service->getAll($clinic->id);

    expect($result)
        ->toHaveCount(3)
        ->each->toBeArray();
});


it('returns the expected basic doctor information', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create([
        'name' => 'Dr. Ahmed',
        'phone' => '01000000000',
        'email' => 'ahmed@example.com',
    ]);

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getAll($clinic->id);

    expect($result->first())->toMatchArray([
        'id' => $doctor->id,
        'name' => 'Dr. Ahmed',
        'phone' => '01000000000',
        'email' => 'ahmed@example.com',
    ]);
});


it('returns the consultation fee from the كشف service', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $service = DoctorService::factory()->create([
        'name' => 'كشف',
    ]);

    doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'doctorService_id' => $service->id,
        'price' => 250,
    ]);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['Consultation_Fee'])
        ->toBe('250.00');
});


it('returns the first كشف service price when doctor has multiple prices', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $consultationService = DoctorService::factory()->create([
        'name' => 'كشف',
    ]);

    doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'doctorService_id' => $consultationService->id,
        'price' => 300,
    ]);

    doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'doctorService_id' => $consultationService->id,
        'price' => 500,
    ]);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['Consultation_Fee'])
        ->toBe('300.00');
});


it('ignores services other than كشف when finding consultation fee', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $service = DoctorService::factory()->create([
        'name' => 'متابعة',
    ]);

    doctor_service_price::factory()->create([
        'doctor_id' => $doctor->id,
        'doctorService_id' => $service->id,
        'price' => 400,
    ]);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['Consultation_Fee'])
        ->toBe('لا توجد خدمة');
});


it('returns no service when doctor has no service prices', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['Consultation_Fee'])
        ->toBe('لا توجد خدمة');
});


it('returns the first speciality only', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $specialityOne = Speciality::factory()->create([
        'name' => 'Cardiology',
    ]);

    $specialityTwo = Speciality::factory()->create([
        'name' => 'Neurology',
    ]);

    $doctor->specialities()->attach([
        $specialityOne->id,
        $specialityTwo->id,
    ]);

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['speciality'])
        ->toBe([
            'id' => $specialityOne->id,
            'name' => 'Cardiology',
        ]);
});


it('returns null speciality when doctor has no speciality', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['speciality'])
        ->toBeNull();
});


it('returns the doctor active status for the requested clinic', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id, [
        'is_active' => true,
    ]);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['is_active'])
        ->toBe(1);
});


it('returns false when doctor is inactive in the requested clinic', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id, [
        'is_active' => false,
    ]);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['is_active'])
        ->toBe(0);
});


it('uses the requested clinic pivot status when doctor belongs to multiple clinics', function () {
    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->clinics()->attach([
        $clinicOne->id => [
            'is_active' => true,
        ],
        $clinicTwo->id => [
            'is_active' => false,
        ],
    ]);

    $result = $this->service->getAll($clinicTwo->id);

    expect($result->first()['is_active'])
        ->toBe(0);
});


it('returns doctor schedules', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $schedules = Schedule::factory()
        ->count(3)
        ->create([
            'doctor_id' => $doctor->id,
        ]);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['schedules'])
        ->toHaveCount(3);
});


it('returns an empty schedule collection when doctor has no schedules', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getAll($clinic->id);

    expect($result->first()['schedules'])
        ->toBeEmpty();
});


it('returns all expected doctor fields', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $result = $this->service->getAll($clinic->id);

    expect($result->first())
        ->toHaveKeys([
            'id',
            'name',
            'phone',
            'email',
            'Consultation_Fee',
            'speciality',
            'is_active',
            'schedules',
            'image',
        ]);
});


it('returns an empty collection when clinic has no doctors', function () {
    $clinic = Clinic::factory()->create();

    $result = $this->service->getAll($clinic->id);

    expect($result)
        ->toBeEmpty();
});


it('throws an exception when clinic does not exist', function () {
    expect(fn () => $this->service->getAll(999999))
        ->toThrow(ModelNotFoundException::class);
});


it('does not modify the database', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinic->doctors()->attach($doctor->id);

    $usersBefore = User::count();
    $clinicsBefore = Clinic::count();
    $doctorsBefore = Doctor::count();
    $servicesBefore = DoctorService::count();
    $servicePricesBefore = doctor_service_price::count();
    $schedulesBefore = Schedule::count();

    $this->service->getAll($clinic->id);

    expect(User::count())->toBe($usersBefore)
        ->and(Clinic::count())->toBe($clinicsBefore)
        ->and(Doctor::count())->toBe($doctorsBefore)
        ->and(DoctorService::count())->toBe($servicesBefore)
        ->and(doctor_service_price::count())->toBe($servicePricesBefore)
        ->and(Schedule::count())->toBe($schedulesBefore);
});


it('returns multiple doctors with independent data', function () {
    $clinic = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create([
        'name' => 'Doctor One',
    ]);

    $doctorTwo = Doctor::factory()->create([
        'name' => 'Doctor Two',
    ]);

    $clinic->doctors()->attach([
        $doctorOne->id,
        $doctorTwo->id,
    ]);

    $result = $this->service->getAll($clinic->id);

    expect($result)->toHaveCount(2)
        ->and($result->pluck('name')->all())
        ->toContain('Doctor One', 'Doctor Two');
});


it('returns consultation fee independently for each doctor', function () {
    $clinic = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create();
    $doctorTwo = Doctor::factory()->create();

    $clinic->doctors()->attach([
        $doctorOne->id,
        $doctorTwo->id,
    ]);

    $service = DoctorService::factory()->create([
        'name' => 'كشف',
    ]);

    doctor_service_price::factory()->create([
        'doctor_id' => $doctorOne->id,
        'doctorService_id' => $service->id,
        'price' => 200,
    ]);

    doctor_service_price::factory()->create([
        'doctor_id' => $doctorTwo->id,
        'doctorService_id' => $service->id,
        'price' => 350,
    ]);

    $result = $this->service->getAll($clinic->id);

    $doctorOneResult = $result->firstWhere('id', $doctorOne->id);
    $doctorTwoResult = $result->firstWhere('id', $doctorTwo->id);

    expect($doctorOneResult['Consultation_Fee'])->toBe('200.00')
        ->and($doctorTwoResult['Consultation_Fee'])->toBe('350.00');
});
