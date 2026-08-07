<?php

use App\Models\Day;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Speciality;
use App\Services\Schedule\ScheduleQueryService;
use Illuminate\Foundation\Testing\RefreshDatabase;


uses(RefreshDatabase::class);


beforeEach(function () {
    $this->service = app(ScheduleQueryService::class);
});


it('returns all doctors', function () {

    Doctor::factory()
        ->count(5)
        ->create();


    $result = $this->service->getAll();


    expect($result)
        ->toHaveCount(5);
});



it('returns only doctor id and name fields', function () {

    $doctor = Doctor::factory()->create([
        'name' => 'Ahmed',
    ]);


    $result = $this->service->getAll()
        ->firstWhere('id', $doctor->id);



    expect($result->name)
        ->toBe('Ahmed');


    expect($result->getAttributes())
        ->not->toHaveKey('email')
        ->not->toHaveKey('phone');
});



it('loads doctor specialities', function () {

    $doctor = Doctor::factory()->create();


    $speciality1 = Speciality::factory()->create([
        'name' => 'Dental',
    ]);

    $speciality2 = Speciality::factory()->create([
        'name' => 'Surgery',
    ]);


    $doctor->specialities()->attach([
        $speciality1->id,
        $speciality2->id,
    ]);



    $result = $this->service->getAll()
        ->firstWhere('id', $doctor->id);



    expect($result->specialities)
        ->toHaveCount(2);


    expect(
        $result->specialities
            ->pluck('name')
            ->toArray()
    )
        ->toContain('Dental', 'Surgery');
});



it('returns empty specialities when doctor has no specialities', function () {

    $doctor = Doctor::factory()->create();


    $result = $this->service->getAll()
        ->firstWhere('id', $doctor->id);



    expect($result->specialities)
        ->toBeEmpty();
});



it('counts doctor schedules correctly', function () {

    $doctor = Doctor::factory()->create();


    Schedule::factory()
        ->count(3)
        ->create([
            'doctor_id' => $doctor->id,
        ]);



    $result = $this->service->getAll()
        ->firstWhere('id', $doctor->id);



    expect($result->schedules_count)
        ->toBe(3);
});



it('returns zero schedules count when doctor has no schedules', function () {

    $doctor = Doctor::factory()->create();


    $result = $this->service->getAll()
        ->firstWhere('id', $doctor->id);



    expect($result->schedules_count)
        ->toBe(0);
});



it('loads schedules with days', function () {

    $doctor = Doctor::factory()->create();


    $schedule = Schedule::factory()->create([
        'doctor_id' => $doctor->id,
    ]);


    $day1 = Day::factory()->create([
        'name' => 'Saturday',
    ]);

    $day2 = Day::factory()->create([
        'name' => 'Sunday',
    ]);



    $schedule->days()->attach([
        $day1->id,
        $day2->id,
    ]);



    $result = $this->service->getAll()
        ->firstWhere('id', $doctor->id);



    expect($result->schedules)
        ->toHaveCount(1);



    expect($result->schedules->first()->days)
        ->toHaveCount(2);



    expect(
        $result->schedules
            ->first()
            ->days
            ->pluck('name')
    )
        ->toContain('Saturday', 'Sunday');
});



it('returns multiple schedules with their own days', function () {

    $doctor = Doctor::factory()->create();


    $schedule1 = Schedule::factory()->create([
        'doctor_id' => $doctor->id,
    ]);

    $schedule2 = Schedule::factory()->create([
        'doctor_id' => $doctor->id,
    ]);


    $day1 = Day::factory()->create();
    $day2 = Day::factory()->create();
    $day3 = Day::factory()->create();



    $schedule1->days()->attach([
        $day1->id,
        $day2->id,
    ]);


    $schedule2->days()->attach([
        $day3->id,
    ]);



    $result = $this->service->getAll()
        ->firstWhere('id', $doctor->id);



    expect($result->schedules)
        ->toHaveCount(2);



    expect(
        $result->schedules
            ->firstWhere('id', $schedule1->id)
            ->days
    )
        ->toHaveCount(2);



    expect(
        $result->schedules
            ->firstWhere('id', $schedule2->id)
            ->days
    )
        ->toHaveCount(1);
});



it('does not mix schedules between doctors', function () {

    $doctor1 = Doctor::factory()->create();

    $doctor2 = Doctor::factory()->create();



    Schedule::factory()
        ->count(2)
        ->create([
            'doctor_id' => $doctor1->id,
        ]);



    Schedule::factory()
        ->count(5)
        ->create([
            'doctor_id' => $doctor2->id,
        ]);



    $result = $this->service->getAll();



    $doctor1Result = $result
        ->firstWhere('id', $doctor1->id);


    $doctor2Result = $result
        ->firstWhere('id', $doctor2->id);



    expect($doctor1Result->schedules_count)
        ->toBe(2);



    expect($doctor2Result->schedules_count)
        ->toBe(5);
});



it('eager loads required relationships', function () {

    Doctor::factory()->create();


    $doctor = $this->service
        ->getAll()
        ->first();



    expect($doctor->relationLoaded('specialities'))
        ->toBeTrue();



    expect($doctor->relationLoaded('schedules'))
        ->toBeTrue();
});



it('returns empty collection when no doctors exist', function () {

    $result = $this->service->getAll();


    expect($result)
        ->toBeEmpty();
});