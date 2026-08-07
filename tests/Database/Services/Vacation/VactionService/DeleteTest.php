<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Vication;
use App\Services\Vacation\VacationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

beforeEach(function () {

    $this->service = app(VacationService::class);

});


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function createDeleteVacation(array $overrides = []): Vication
{
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->clinics()->attach($clinic->id);


    return Vication::factory()->create(array_merge([
        'doctor_id' => $doctor->id,
        'status' => 'upcoming',
    ], $overrides));
}


/*
|--------------------------------------------------------------------------
| Successful deletion
|--------------------------------------------------------------------------
*/


it('deletes vacation successfully', function () {

    $vacation = createDeleteVacation();


    $result = $this->service->delete(
        $vacation->id,
        $vacation->doctor->clinics->first()->id
    );


    expect($result)
        ->toBeTrue();


    expect(
        Vication::find($vacation->id)
    )
        ->toBeNull();

});



/*
|--------------------------------------------------------------------------
| Return value
|--------------------------------------------------------------------------
*/


it('returns boolean true after successful deletion', function () {

    $vacation = createDeleteVacation();

    $clinicId = $vacation
        ->doctor
        ->clinics
        ->first()
        ->id;


    $result = $this->service->delete(
        $vacation->id,
        $clinicId
    );


    expect($result)
        ->toBeTrue();

});



/*
|--------------------------------------------------------------------------
| Not found cases
|--------------------------------------------------------------------------
*/


it('throws exception when vacation does not exist', function () {

    $this->service->delete(
        999999,
        1
    );

})
->throws(ModelNotFoundException::class);



it('does not delete vacation when clinic does not own it', function () {

    $vacation = createDeleteVacation();


    $anotherClinic = Clinic::factory()->create();


    $this->service->delete(
        $vacation->id,
        $anotherClinic->id
    );

})
->throws(ModelNotFoundException::class);



/*
|--------------------------------------------------------------------------
| Ownership isolation
|--------------------------------------------------------------------------
*/


it('deletes only vacation belonging to requested clinic', function () {

    $clinicOne = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create();

    $doctorOne->clinics()->attach($clinicOne->id);


    $clinicTwo = Clinic::factory()->create();

    $doctorTwo = Doctor::factory()->create();

    $doctorTwo->clinics()->attach($clinicTwo->id);



    $vacationOne = Vication::factory()->create([
        'doctor_id' => $doctorOne->id,
    ]);


    $vacationTwo = Vication::factory()->create([
        'doctor_id' => $doctorTwo->id,
    ]);



    $this->service->delete(
        $vacationOne->id,
        $clinicOne->id
    );



    expect(
        Vication::find($vacationOne->id)
    )
        ->toBeNull();



    expect(
        Vication::find($vacationTwo->id)
    )
        ->not()
        ->toBeNull();

});



/*
|--------------------------------------------------------------------------
| Database integrity
|--------------------------------------------------------------------------
*/


it('does not delete doctor when deleting vacation', function () {

    $vacation = createDeleteVacation();


    $doctorId = $vacation->doctor_id;


    $clinicId = $vacation
        ->doctor
        ->clinics
        ->first()
        ->id;



    $this->service->delete(
        $vacation->id,
        $clinicId
    );



    expect(
        Doctor::find($doctorId)
    )
        ->not()
        ->toBeNull();

});



it('does not delete clinic when deleting vacation', function () {

    $vacation = createDeleteVacation();


    $clinic = $vacation
        ->doctor
        ->clinics
        ->first();



    $this->service->delete(
        $vacation->id,
        $clinic->id
    );


    expect(
        Clinic::find($clinic->id)
    )
        ->not()
        ->toBeNull();

});



/*
|--------------------------------------------------------------------------
| Multiple records
|--------------------------------------------------------------------------
*/


it('deletes correct vacation among many vacations', function () {

    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $doctor->clinics()->attach($clinic->id);



    $vacations = Vication::factory()
        ->count(10)
        ->create([
            'doctor_id' => $doctor->id,
        ]);



    $target = $vacations->first();



    $this->service->delete(
        $target->id,
        $clinic->id
    );



    expect(
        Vication::find($target->id)
    )
        ->toBeNull();



    expect(
        Vication::count()
    )
        ->toBe(9);

});



/*
|--------------------------------------------------------------------------
| Same request twice
|--------------------------------------------------------------------------
*/


it('cannot delete same vacation twice', function () {

    $vacation = createDeleteVacation();


    $clinicId = $vacation
        ->doctor
        ->clinics
        ->first()
        ->id;



    $this->service->delete(
        $vacation->id,
        $clinicId
    );



    $this->service->delete(
        $vacation->id,
        $clinicId
    );

})
->throws(ModelNotFoundException::class);