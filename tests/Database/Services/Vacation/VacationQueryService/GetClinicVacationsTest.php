<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Vication;
use App\Services\Vacation\VacationQueryService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;


uses(RefreshDatabase::class);


beforeEach(function () {
    $this->service = app(VacationQueryService::class);
});


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function createClinicVacation(array $overrides = []): Vication
{
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->clinics()->attach($clinic->id);


    return Vication::factory()->create(array_merge([
        'doctor_id' => $doctor->id,
        'start_date' => now()->toDateString(),
        'end_date' => now()->addDays(5)->toDateString(),
        'reason' => 'Annual vacation',
        'status' => 'approved',
    ], $overrides));
}


/*
|--------------------------------------------------------------------------
| Return type
|--------------------------------------------------------------------------
*/

it('returns a paginator instance', function () {

    createClinicVacation();


    $result = $this->service->getClinicVacations(
        Clinic::first()->id
    );


    expect($result)
        ->toBeInstanceOf(LengthAwarePaginator::class);
});


/*
|--------------------------------------------------------------------------
| Empty result
|--------------------------------------------------------------------------
*/

it('returns empty paginator when clinic has no vacations', function () {

    $clinic = Clinic::factory()->create();


    $result = $this->service->getClinicVacations(
        $clinic->id
    );


    expect($result->total())
        ->toBe(0);
});


/*
|--------------------------------------------------------------------------
| Clinic filtering
|--------------------------------------------------------------------------
*/

it('returns only vacations belonging to requested clinic', function () {

    $clinicOne = Clinic::factory()->create();

    $clinicTwo = Clinic::factory()->create();


    createClinicVacation([
        'doctor_id' => Doctor::factory()->create()->id,
    ]);


    $doctorOne = Doctor::factory()->create();
    $doctorOne->clinics()->attach($clinicOne->id);

    $vacationOne = Vication::factory()->create([
        'doctor_id' => $doctorOne->id,
    ]);


    $doctorTwo = Doctor::factory()->create();
    $doctorTwo->clinics()->attach($clinicTwo->id);

    $vacationTwo = Vication::factory()->create([
        'doctor_id' => $doctorTwo->id,
    ]);


    $result = $this->service->getClinicVacations(
        $clinicOne->id
    );


    expect($result->pluck('id'))
        ->toContain($vacationOne->id);


    expect($result->pluck('id'))
        ->not->toContain($vacationTwo->id);
});


/*
|--------------------------------------------------------------------------
| Doctor relation
|--------------------------------------------------------------------------
*/

it('loads doctor relation', function () {

    $vacation = createClinicVacation();


    $result = $this->service->getClinicVacations(
        $vacation->doctor->clinics->first()->id
    );


    $item = $result->first();


    expect($item->relationLoaded('doctor'))
        ->toBeTrue();


    expect($item->doctor)
        ->not->toBeNull();
});


it('returns doctor only with selected columns', function () {

    $vacation = createClinicVacation();


    $result = $this->service->getClinicVacations(
        $vacation->doctor->clinics->first()->id
    );


    $doctor = $result->first()->doctor;


    expect($doctor->getAttributes())
        ->toHaveKeys([
            'id',
            'name',
        ]);


    expect($doctor->getAttributes())
        ->not->toHaveKey('email');
});


/*
|--------------------------------------------------------------------------
| Selected vacation columns
|--------------------------------------------------------------------------
*/

it('returns only required vacation columns', function () {

    $vacation = createClinicVacation();


    $result = $this->service->getClinicVacations(
        $vacation->doctor->clinics->first()->id
    );


    expect($result->first()->getAttributes())
        ->toHaveKeys([
            'id',
            'start_date',
            'end_date',
            'reason',
            'doctor_id',
            'status',
        ]);
});


/*
|--------------------------------------------------------------------------
| Doctor without clinic
|--------------------------------------------------------------------------
*/

it('does not return vacation of doctor not assigned to clinic', function () {

    $clinic = Clinic::factory()->create();


    $doctor = Doctor::factory()->create();


    $vacation = Vication::factory()->create([
        'doctor_id' => $doctor->id,
    ]);


    $result = $this->service->getClinicVacations(
        $clinic->id
    );


    expect($result->pluck('id'))
        ->not->toContain($vacation->id);
});


/*
|--------------------------------------------------------------------------
| Multiple doctors
|--------------------------------------------------------------------------
*/

it('returns vacations from all doctors inside clinic', function () {

    $clinic = Clinic::factory()->create();


    $doctorOne = Doctor::factory()->create();
    $doctorOne->clinics()->attach($clinic->id);


    $doctorTwo = Doctor::factory()->create();
    $doctorTwo->clinics()->attach($clinic->id);


    $vacationOne = Vication::factory()->create([
        'doctor_id' => $doctorOne->id,
    ]);


    $vacationTwo = Vication::factory()->create([
        'doctor_id' => $doctorTwo->id,
    ]);


    $result = $this->service->getClinicVacations(
        $clinic->id
    );


    expect($result->pluck('id'))
        ->toContain($vacationOne->id)
        ->toContain($vacationTwo->id);
});


/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

it('paginates vacations by 10 records', function () {

    $clinic = Clinic::factory()->create();


    $doctor = Doctor::factory()->create();

    $doctor->clinics()->attach($clinic->id);


    Vication::factory()
        ->count(25)
        ->create([
            'doctor_id' => $doctor->id,
        ]);


    $result = $this->service->getClinicVacations(
        $clinic->id
    );


    expect($result->perPage())
        ->toBe(10);


    expect($result->total())
        ->toBe(25);


    expect($result->count())
        ->toBe(10);
});


/*
|--------------------------------------------------------------------------
| Large dataset
|--------------------------------------------------------------------------
*/

it('handles many vacations correctly', function () {

    $clinic = Clinic::factory()->create();


    $doctor = Doctor::factory()->create();

    $doctor->clinics()->attach($clinic->id);


    Vication::factory()
        ->count(100)
        ->create([
            'doctor_id' => $doctor->id,
        ]);


    $result = $this->service->getClinicVacations(
        $clinic->id
    );


    expect($result->total())
        ->toBe(100);
});


/*
|--------------------------------------------------------------------------
| Database integrity
|--------------------------------------------------------------------------
*/

it('does not modify database', function () {

    $vacation = createClinicVacation();


    $before = Vication::count();


    $this->service->getClinicVacations(
        $vacation->doctor->clinics->first()->id
    );


    expect(Vication::count())
        ->toBe($before);
});


/*
|--------------------------------------------------------------------------
| Non existing clinic
|--------------------------------------------------------------------------
*/

it('returns empty result for non existing clinic id', function () {

    $result = $this->service->getClinicVacations(
        999999
    );


    expect($result->total())
        ->toBe(0);
});