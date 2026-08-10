<?php

use App\Enums\VacationStatus;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Vacation;
use App\Services\Vacation\VacationStatisticsService;

beforeEach(function () {

    $this->service = app(VacationStatisticsService::class);

});

function statisticsClinic()
{
    return Clinic::factory()->create();
}

function statisticsDoctor()
{
    return Doctor::factory()->create();
}

function createVacationForClinic(
    Clinic $clinic,
    Doctor $doctor,
    string $status
) {
    $doctor->clinics()->syncWithoutDetaching([
        $clinic->id,
    ]);

    return Vacation::factory()->create([

        'clinic_id' => $clinic->id,

        'doctor_id' => $doctor->id,

        'status' => $status,

    ]);
}

/*
|--------------------------------------------------------------------------
| Empty statistics
|--------------------------------------------------------------------------
*/

it('returns zero statistics when clinic has no vacations', function () {

    $clinic = statisticsClinic();

    $result = $this->service
        ->getStatistics($clinic->id);

    expect($result['total'])
        ->toBe(0);

    expect($result['upcoming'])
        ->toBe(0);

    expect($result['active'])
        ->toBe(0);

    expect($result['ended'])
        ->toBe(0);

});

/*
|--------------------------------------------------------------------------
| Total count
|--------------------------------------------------------------------------
*/

it('returns correct total vacations count', function () {

    $clinic = statisticsClinic();

    $doctor = statisticsDoctor();

    createVacationForClinic(
        $clinic,
        $doctor,
                VacationStatus::UPCOMING->value

    );

    createVacationForClinic(
        $clinic,
        $doctor,
               VacationStatus::ACTIVE->value

    );

    $result = $this->service
        ->getStatistics($clinic->id);

    expect($result['total'])
        ->toBe(2);

});

/*
|--------------------------------------------------------------------------
| Status counting
|--------------------------------------------------------------------------
*/

it('counts each vacation status correctly', function () {

    $clinic = statisticsClinic();

    $doctor = statisticsDoctor();

    createVacationForClinic(
        $clinic,
        $doctor,
        VacationStatus::UPCOMING->value
    );

    createVacationForClinic(
        $clinic,
        $doctor,
               VacationStatus::UPCOMING->value

    );

    createVacationForClinic(
        $clinic,
        $doctor,
               VacationStatus::ACTIVE->value

    );

    createVacationForClinic(
        $clinic,
        $doctor,
               VacationStatus::ENDED->value

    );

    $result = $this->service
        ->getStatistics($clinic->id);

    expect($result['total'])
        ->toBe(4);

    expect($result['upcoming'])
        ->toBe(2);

    expect($result['active'])
        ->toBe(1);

    expect($result['ended'])
        ->toBe(1);

});

/*
|--------------------------------------------------------------------------
| Clinic isolation
|--------------------------------------------------------------------------
*/

it('does not include other clinics vacations', function () {

    $clinicOne = statisticsClinic();

    $clinicTwo = statisticsClinic();

    $doctorOne = statisticsDoctor();

    $doctorTwo = statisticsDoctor();

    createVacationForClinic(
        $clinicOne,
        $doctorOne,
        VacationStatus::ACTIVE->value
    );

    createVacationForClinic(
        $clinicTwo,
        $doctorTwo,
        VacationStatus::ACTIVE->value

    );

    $result = $this->service
        ->getStatistics($clinicOne->id);

    expect($result['total'])
        ->toBe(1);

    expect($result['active'])
        ->toBe(1);

});

/*
|--------------------------------------------------------------------------
| Same doctor multiple vacations
|--------------------------------------------------------------------------
*/

it('counts multiple vacations for same doctor', function () {

    $clinic = statisticsClinic();

    $doctor = statisticsDoctor();

    createVacationForClinic(
        $clinic,
        $doctor,
        VacationStatus::UPCOMING->value,
    );

    createVacationForClinic(
        $clinic,
        $doctor,
        VacationStatus::ENDED->value,
    );

    $result = $this->service
        ->getStatistics($clinic->id);

    expect($result['total'])
        ->toBe(2);

});

/*
|--------------------------------------------------------------------------
| Multiple doctors
|--------------------------------------------------------------------------
*/

it('counts vacations from different doctors', function () {

    $clinic = statisticsClinic();

    foreach (range(1, 5) as $index) {

        createVacationForClinic(
            $clinic,
            statisticsDoctor(),
            'active'
        );

    }

    $result = $this->service
        ->getStatistics($clinic->id);

    expect($result['active'])
        ->toBe(5);

    expect($result['total'])
        ->toBe(5);

});

/*
|--------------------------------------------------------------------------
| Return type
|--------------------------------------------------------------------------
*/

it('returns statistics object instance', function () {

    $clinic = statisticsClinic();

    $result = $this->service
        ->getStatistics($clinic->id);

    expect($result)->toBeArray();
});

/*
|--------------------------------------------------------------------------
| Integer casting
|--------------------------------------------------------------------------
*/

it('returns integer values not strings', function () {

    $clinic = statisticsClinic();

    $result = $this->service
        ->getStatistics($clinic->id);

    expect($result['total'])
        ->toBeInt();

    expect($result['upcoming'])
        ->toBeInt();

    expect($result['active'])
        ->toBeInt();

    expect($result['ended'])
        ->toBeInt();

});

/*
|--------------------------------------------------------------------------
| Large dataset
|--------------------------------------------------------------------------
*/

it('handles large number of vacations correctly', function () {

    $clinic = statisticsClinic();

    $doctor = statisticsDoctor();

    $doctor->clinics()->attach($clinic->id);

    Vacation::factory()
        ->count(200)
        ->create([
            'clinic_id' => $clinic->id,
            'doctor_id' => $doctor->id,
            'status' => 'active',
        ]);

    $result = $this->service
        ->getStatistics($clinic->id);

    expect($result['total'])
        ->toBe(200);

    expect($result['active'])
        ->toBe(200);

});
