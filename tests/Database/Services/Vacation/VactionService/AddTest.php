<?php

use App\Exceptions\HasVicationException;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Vication;
use App\Services\Vacation\VacationService;


beforeEach(function () {
    $this->service = app(VacationService::class);
});


function createAddVacationClinic()
{
    return Clinic::factory()->create();
}


function createAddVacationDoctor()
{
    return Doctor::factory()->create();
}


function vacationData(array $override = []): array
{
    return array_merge([

        'doctor_id' => Doctor::factory()->create()->id,

        'start_date' => now()
            ->addDays(5)
            ->toDateString(),

        'end_date' => now()
            ->addDays(10)
            ->toDateString(),

        'reason' => 'Annual vacation',

        'status' => 'upcoming',

    ], $override);
}


it('creates vacation successfully', function () {

    $clinic = createAddVacationClinic();
    $doctor = createAddVacationDoctor();

    $result = $this->service->add(
        vacationData([
            'doctor_id' => $doctor->id,
        ]),
        $clinic->id
    );

    expect($result)
        ->toBeInstanceOf(Vication::class);

    expect(Vication::count())
        ->toBe(1);

});


it('stores correct vacation data in database', function () {

    $clinic = createAddVacationClinic();
    $doctor = createAddVacationDoctor();

    $data = vacationData([
        'doctor_id' => $doctor->id,
        'reason' => 'Medical leave',
    ]);

    $vacation = $this->service->add(
        $data,
        $clinic->id
    );


    expect($vacation->clinic_id)
        ->toBe($clinic->id);

    expect($vacation->doctor_id)
        ->toBe($doctor->id);

    expect($vacation->reason)
        ->toBe('Medical leave');

});


it('throws exception when doctor already has vacation in same clinic', function () {

    $clinic = createAddVacationClinic();
    $doctor = createAddVacationDoctor();


    Vication::factory()->create([

        'clinic_id' => $clinic->id,

        'doctor_id' => $doctor->id,

        'start_date' => now()
            ->addDays(5)
            ->toDateString(),

        'end_date' => now()
            ->addDays(10)
            ->toDateString(),

        'status' => 'active',

    ]);


    $this->service->add(

        vacationData([

            'doctor_id' => $doctor->id,

            'start_date' => now()
                ->addDays(5)
                ->toDateString(),

            'end_date' => now()
                ->addDays(10)
                ->toDateString(),

        ]),

        $clinic->id
    );


})->throws(HasVicationException::class);



it('does not create vacation when duplicate exists', function () {

    $clinic = createAddVacationClinic();

    $doctor = createAddVacationDoctor();


    $data = vacationData([
        'doctor_id' => $doctor->id,
    ]);


    Vication::factory()->create([

        'clinic_id' => $clinic->id,

        'doctor_id' => $doctor->id,

        'start_date' => $data['start_date'],

        'end_date' => $data['end_date'],

        'status' => 'upcoming',

    ]);


    $before = Vication::count();


    expect(fn () => $this->service->add(
        $data,
        $clinic->id
    ))->toThrow(HasVicationException::class);


    expect(Vication::count())
        ->toBe($before);

});



it('allows same doctor to have vacation in different clinic', function () {

    $doctor = createAddVacationDoctor();

    $clinicOne = createAddVacationClinic();

    $clinicTwo = createAddVacationClinic();


    Vication::factory()->create([

        'clinic_id' => $clinicOne->id,

        'doctor_id' => $doctor->id,

        'status' => 'active',

    ]);


    $vacation = $this->service->add(

        vacationData([
            'doctor_id' => $doctor->id,
        ]),

        $clinicTwo->id
    );


    expect($vacation->clinic_id)
        ->toBe($clinicTwo->id);

});



it('stores all vacation statuses correctly', function () {

    foreach ([
        'upcoming',
        'active',
        'ended',
    ] as $status) {


        $clinic = createAddVacationClinic();

        $doctor = createAddVacationDoctor();


        $vacation = $this->service->add(

            vacationData([
                'doctor_id' => $doctor->id,
                'status' => $status,
            ]),

            $clinic->id

        );


        expect($vacation->status)
            ->toBe($status);

    }

});



it('creates vacation without reason', function () {

    $clinic = createAddVacationClinic();

    $doctor = createAddVacationDoctor();


    $vacation = $this->service->add(

        vacationData([
            'doctor_id' => $doctor->id,
            'reason' => null,
        ]),

        $clinic->id
    );


    expect($vacation->reason)
        ->toBeNull();

});



it('creates vacations for different doctors in same clinic', function () {

    $clinic = createAddVacationClinic();

    $doctorOne = createAddVacationDoctor();

    $doctorTwo = createAddVacationDoctor();


    $this->service->add(

        vacationData([
            'doctor_id' => $doctorOne->id,
        ]),

        $clinic->id
    );


    $this->service->add(

        vacationData([
            'doctor_id' => $doctorTwo->id,
        ]),

        $clinic->id
    );


    expect(Vication::count())
        ->toBe(2);

});



it('creates vacation correctly with many existing vacations', function () {

    Vication::factory()
        ->count(50)
        ->create();


    $clinic = createAddVacationClinic();

    $doctor = createAddVacationDoctor();


    $result = $this->service->add(

        vacationData([
            'doctor_id' => $doctor->id,
        ]),

        $clinic->id
    );


    expect($result)
        ->toBeInstanceOf(Vication::class);

});