<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Vacation;
use App\Services\Vacation\VacationValidationService;

beforeEach(function () {

    $this->service = app(VacationValidationService::class);

});


function validationClinic()
{
    return Clinic::factory()->create();
}


function validationDoctor()
{
    return Doctor::factory()->create();
}


function createValidationVacation(
    int $doctorId,
    int $clinicId,
    string $status = 'active'
) {

    return Vacation::factory()->create([

        'doctor_id' => $doctorId,

        'clinic_id' => $clinicId,

        'status' => $status,

    ]);

}



/*
|--------------------------------------------------------------------------
| Basic existence
|--------------------------------------------------------------------------
*/


it('returns true when doctor has active vacation in same clinic', function () {


    $clinic = validationClinic();

    $doctor = validationDoctor();


    createValidationVacation(
        $doctor->id,
        $clinic->id,
        'active'
    );


    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id
        )
    )->toBeTrue();


});



it('returns true when doctor has upcoming vacation in same clinic', function () {


    $clinic = validationClinic();

    $doctor = validationDoctor();


    createValidationVacation(
        $doctor->id,
        $clinic->id,
        'upcoming'
    );


    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id
        )
    )->toBeTrue();


});



/*
|--------------------------------------------------------------------------
| No conflict cases
|--------------------------------------------------------------------------
*/


it('returns false when doctor has no vacation', function () {


    $clinic = validationClinic();

    $doctor = validationDoctor();


    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id
        )
    )->toBeFalse();


});



it('returns false when vacation belongs to another clinic', function () {


    $clinicOne = validationClinic();

    $clinicTwo = validationClinic();


    $doctor = validationDoctor();



    createValidationVacation(
        $doctor->id,
        $clinicOne->id,
        'active'
    );


    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinicTwo->id
        )
    )->toBeFalse();


});



it('returns false when vacation belongs to another doctor', function () {


    $clinic = validationClinic();


    $doctorOne = validationDoctor();

    $doctorTwo = validationDoctor();



    createValidationVacation(
        $doctorOne->id,
        $clinic->id,
        'active'
    );



    expect(
        $this->service->hasVacation(
            $doctorTwo->id,
            $clinic->id
        )
    )->toBeFalse();


});



/*
|--------------------------------------------------------------------------
| Status business rules
|--------------------------------------------------------------------------
*/


it('ignores ended vacations', function () {


    $clinic = validationClinic();

    $doctor = validationDoctor();


    createValidationVacation(
        $doctor->id,
        $clinic->id,
        'ended'
    );


    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id
        )
    )->toBeFalse();


});



it('ignores approved vacations', function () {


    $clinic = validationClinic();

    $doctor = validationDoctor();


    createValidationVacation(
        $doctor->id,
        $clinic->id,
        'approved'
    );


    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id
        )
    )->toBeFalse();


});



/*
|--------------------------------------------------------------------------
| Ignore ID (update scenario)
|--------------------------------------------------------------------------
*/


it('ignores current vacation when ignore id provided', function () {


    $clinic = validationClinic();

    $doctor = validationDoctor();



    $vacation = createValidationVacation(
        $doctor->id,
        $clinic->id,
        'active'
    );



    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id,
            $vacation->id
        )
    )->toBeFalse();


});



it('detects another vacation even when ignoring one vacation', function () {


    $clinic = validationClinic();

    $doctor = validationDoctor();



    $first = createValidationVacation(
        $doctor->id,
        $clinic->id,
        'active'
    );


    createValidationVacation(
        $doctor->id,
        $clinic->id,
        'upcoming'
    );



    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id,
            $first->id
        )
    )->toBeTrue();


});



/*
|--------------------------------------------------------------------------
| Multiple records
|--------------------------------------------------------------------------
*/


it('handles multiple vacations correctly', function () {


    $clinic = validationClinic();

    $doctor = validationDoctor();



    Vacation::factory()
        ->count(10)
        ->create([
            'doctor_id' => $doctor->id,
            'clinic_id' => $clinic->id,
            'status' => 'ended',
        ]);



    createValidationVacation(
        $doctor->id,
        $clinic->id,
        'active'
    );



    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id
        )
    )->toBeTrue();


});



/*
|--------------------------------------------------------------------------
| Large dataset
|--------------------------------------------------------------------------
*/


it('works correctly with many vacations', function () {


    $clinic = validationClinic();

    $doctor = validationDoctor();



    Vacation::factory()
        ->count(200)
        ->create([
            'doctor_id' => $doctor->id,
            'clinic_id' => $clinic->id,
            'status' => 'ended',
        ]);



    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id
        )
    )->toBeFalse();



    createValidationVacation(
        $doctor->id,
        $clinic->id,
        'active'
    );



    expect(
        $this->service->hasVacation(
            $doctor->id,
            $clinic->id
        )
    )->toBeTrue();


});