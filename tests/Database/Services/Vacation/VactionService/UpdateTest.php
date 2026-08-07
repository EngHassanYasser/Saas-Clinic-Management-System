<?php

use App\Exceptions\HasVicationException;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Vication;
use App\Services\Vacation\VacationService;
use Illuminate\Database\Eloquent\ModelNotFoundException;


beforeEach(function () {

    $this->service = app(VacationService::class);

});


function updateVacationClinic()
{
    return Clinic::factory()->create();
}


function updateVacationDoctor()
{
    return Doctor::factory()->create();
}


function updateVacationData(array $override = [])
{
    return array_merge([

        'start_date' => now()->addDays(10)->toDateString(),

        'end_date' => now()->addDays(15)->toDateString(),

        'reason' => 'Updated vacation',

        'status' => 'approved',

        'doctor_id' => Doctor::factory()->create()->id,

    ], $override);
}


/*
|--------------------------------------------------------------------------
| Successful update
|--------------------------------------------------------------------------
*/


it('updates vacation successfully', function () {


    $clinic = updateVacationClinic();

    $doctor = updateVacationDoctor();


    $vacation = Vication::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'status' => 'upcoming',
    ]);


    $result = $this->service->update(
        updateVacationData([
            'doctor_id' => $doctor->id,
        ]),
        $vacation->id,
        $clinic->id
    );


    expect($result)
        ->toBeTrue();


    expect($vacation->fresh()->reason)
        ->toBe('Updated vacation');


});


/*
|--------------------------------------------------------------------------
| Database persistence
|--------------------------------------------------------------------------
*/


it('stores updated values in database', function () {


    $clinic = updateVacationClinic();

    $doctor = updateVacationDoctor();


    $vacation = Vication::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
    ]);


    $data = [
        'doctor_id' => $doctor->id,
        'start_date' => now()->addDays(20)->toDateString(),
        'end_date' => now()->addDays(25)->toDateString(),
        'reason' => 'Medical leave',
        'status' => 'active',
    ];


    $this->service->update(
        $data,
        $vacation->id,
        $clinic->id
    );


    $this->assertDatabaseHas('vications', [

        'id' => $vacation->id,
        'reason' => 'Medical leave',
        'status' => 'active',

    ]);

});


/*
|--------------------------------------------------------------------------
| Clinic isolation
|--------------------------------------------------------------------------
*/


it('cannot update vacation from another clinic', function () {


    $clinicOne = updateVacationClinic();

    $clinicTwo = updateVacationClinic();


    $doctor = updateVacationDoctor();


    $vacation = Vication::factory()->create([
        'clinic_id' => $clinicOne->id,
        'doctor_id' => $doctor->id,
    ]);


    $this->service->update(
        updateVacationData([
            'doctor_id' => $doctor->id,
        ]),
        $vacation->id,
        $clinicTwo->id
    );


})
->throws(ModelNotFoundException::class);



/*
|--------------------------------------------------------------------------
| Duplicate validation
|--------------------------------------------------------------------------
*/


it('throws exception when updated vacation conflicts with another vacation', function () {


    $clinic = updateVacationClinic();

    $doctor = updateVacationDoctor();


    $currentVacation = Vication::factory()->create([

        'clinic_id' => $clinic->id,

        'doctor_id' => $doctor->id,

        'status' => 'upcoming',

    ]);


    Vication::factory()->create([

        'clinic_id' => $clinic->id,

        'doctor_id' => $doctor->id,

        'status' => 'active',

    ]);


    expect(fn () => $this->service->update(

        updateVacationData([
            'doctor_id' => $doctor->id,
        ]),

        $currentVacation->id,

        $clinic->id

    ))->toThrow(HasVicationException::class);


});



/*
|--------------------------------------------------------------------------
| Ignore current vacation
|--------------------------------------------------------------------------
*/


it('allows updating same vacation without duplicate error', function () {


    $clinic = updateVacationClinic();

    $doctor = updateVacationDoctor();


    $vacation = Vication::factory()->create([

        'clinic_id' => $clinic->id,

        'doctor_id' => $doctor->id,

        'status' => 'upcoming',

    ]);


    $result = $this->service->update(

        updateVacationData([
            'doctor_id' => $doctor->id,
        ]),

        $vacation->id,

        $clinic->id

    );


    expect($result)
        ->toBeTrue();


});



/*
|--------------------------------------------------------------------------
| Optional fields
|--------------------------------------------------------------------------
*/


it('allows updating vacation without reason', function () {


    $clinic = updateVacationClinic();

    $doctor = updateVacationDoctor();


    $vacation = Vication::factory()->create([

        'clinic_id' => $clinic->id,

        'doctor_id' => $doctor->id,

    ]);


    $this->service->update(

        updateVacationData([
            'doctor_id' => $doctor->id,
            'reason' => null,
        ]),

        $vacation->id,

        $clinic->id

    );


    expect($vacation->fresh()->reason)
        ->toBeNull();


});



/*
|--------------------------------------------------------------------------
| Status updates
|--------------------------------------------------------------------------
*/


it('updates all allowed statuses', function () {


    $statuses = [

        'upcoming',
        'approved',
        'active',
        'ended',

    ];


    foreach ($statuses as $status) {


        $clinic = updateVacationClinic();

        $doctor = updateVacationDoctor();


        $vacation = Vication::factory()->create([

            'clinic_id' => $clinic->id,

            'doctor_id' => $doctor->id,

        ]);


        $this->service->update(

            updateVacationData([
                'doctor_id' => $doctor->id,
                'status' => $status,
            ]),

            $vacation->id,

            $clinic->id

        );


        expect($vacation->fresh()->status)
            ->toBe($status);

    }


});



/*
|--------------------------------------------------------------------------
| Many records performance
|--------------------------------------------------------------------------
*/


it('updates correctly when many vacations exist', function () {


    $clinic = updateVacationClinic();


    Vication::factory()
        ->count(100)
        ->create();


    $doctor = updateVacationDoctor();


    $vacation = Vication::factory()->create([

        'clinic_id' => $clinic->id,

        'doctor_id' => $doctor->id,

    ]);


    $result = $this->service->update(

        updateVacationData([
            'doctor_id' => $doctor->id,
        ]),

        $vacation->id,

        $clinic->id

    );


    expect($result)
        ->toBeTrue();


});