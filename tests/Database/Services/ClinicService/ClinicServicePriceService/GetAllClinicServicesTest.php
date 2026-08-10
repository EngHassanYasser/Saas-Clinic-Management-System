<?php

use App\Models\Clinic;
use App\Models\DoctorService;
use App\Models\Doctor;
use App\Models\Clinic_doctor_medicalService;
use App\Services\ServiceCatalog\DoctorServicePriceService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->service = app(DoctorServicePriceService::class);
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function createDoctorServicePrice(): array
{
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinicService = DoctorService::factory()->create();

    $medicalServicePrice = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'medicalService_id' => $clinicService->id,
    ]);

    return [
        'clinic' => $clinic,
        'doctor' => $doctor,
        'clinicService' => $clinicService,
        'medicalServicePrice' => $medicalServicePrice,
    ];
}
/*
|--------------------------------------------------------------------------
| Return type
|--------------------------------------------------------------------------
*/

it('returns a collection', function () {
    createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    expect($result)
        ->toBeInstanceOf(Collection::class);
});

/*
|--------------------------------------------------------------------------
| Empty database
|--------------------------------------------------------------------------
*/

it('returns an empty collection when there are no clinic services', function () {

    $result = $this->service->getAllDoctorServices();

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

/*
|--------------------------------------------------------------------------
| Get all records
|--------------------------------------------------------------------------
*/

it('returns all doctor service prices', function () {
    $first = createDoctorServicePrice();
    $second = createDoctorServicePrice();
    $third = createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    expect($result)
        ->toHaveCount(3);

    expect($result->pluck('id')->sort()->values()->all())
        ->toBe(
            collect([
                $first['medicalServicePrice']->id,
                $second['medicalServicePrice']->id,
                $third['medicalServicePrice']->id,
            ])->sort()->values()->all()
        );
});

/*
|--------------------------------------------------------------------------
| Correct model
|--------------------------------------------------------------------------
*/

it('returns Clinic_doctor_medicalService models', function () {
    createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    expect($result->every(
        fn (Clinic_doctor_medicalService $item) => $item instanceof Clinic_doctor_medicalService
    ))->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Database persistence
|--------------------------------------------------------------------------
*/

it('returns records that actually exist in the database', function () {
    $context = createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    expect(
        $result->contains(
            fn (Clinic_doctor_medicalService $item) => $item->id === $context['medicalServicePrice']->id
        )
    )->toBeTrue();

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $context['medicalServicePrice']->id)
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Eager loading - clinic
|--------------------------------------------------------------------------
*/

it('eager loads the clinic relationship', function () {
    $context = createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    $item = $result->firstWhere(
        'id',
        $context['medicalServicePrice']->id
    );

    expect($item->relationLoaded('clinic'))
        ->toBeTrue();

    expect($item->clinic)
        ->not->toBeNull()
        ->toBeInstanceOf(Clinic::class)
        ->and($item->clinic->id)
        ->toBe($context['clinic']->id);
});

/*
|--------------------------------------------------------------------------
| Eager loading - doctor
|--------------------------------------------------------------------------
*/

it('eager loads the doctor relationship', function () {
    $context = createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    $item = $result->firstWhere(
        'id',
        $context['medicalServicePrice']->id
    );

    expect($item->relationLoaded('doctor'))
        ->toBeTrue();

    expect($item->doctor)
        ->not->toBeNull()
        ->toBeInstanceOf(Doctor::class)
        ->and($item->doctor->id)
        ->toBe($context['doctor']->id);
});

/*
|--------------------------------------------------------------------------
| Eager loading - clinic service
|--------------------------------------------------------------------------
*/

it('eager loads the medicalService relationship', function () {
    $context = createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    $item = $result->firstWhere(
        'id',
        $context['medicalServicePrice']->id
    );

    expect($item->relationLoaded('medicalService'))
        ->toBeTrue();

    expect($item->medicalService)
        ->not->toBeNull()
        ->toBeInstanceOf(DoctorService::class)
        ->and($item->medicalService->id)
        ->toBe($context['clinicService']->id);
});

/*
|--------------------------------------------------------------------------
| All relationships are eager loaded
|--------------------------------------------------------------------------
*/

it('eager loads all required relationships for every record', function () {
    createDoctorServicePrice();
    createDoctorServicePrice();
    createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    expect($result)->toHaveCount(3);

    foreach ($result as $item) {
        expect($item->relationLoaded('clinic'))
            ->toBeTrue();

        expect($item->relationLoaded('doctor'))
            ->toBeTrue();

        expect($item->relationLoaded('medicalService'))
            ->toBeTrue();
    }
});

/*
|--------------------------------------------------------------------------
| Relationship correctness
|--------------------------------------------------------------------------
*/

it('returns the correct related models for every record', function () {
    $first = createDoctorServicePrice();
    $second = createDoctorServicePrice();
    $third = createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    $firstResult = $result->firstWhere(
        'id',
        $first['medicalServicePrice']->id
    );

    $secondResult = $result->firstWhere(
        'id',
        $second['medicalServicePrice']->id
    );

    $thirdResult = $result->firstWhere(
        'id',
        $third['medicalServicePrice']->id
    );

    expect($firstResult->clinic->id)
        ->toBe($first['clinic']->id);

    expect($firstResult->doctor->id)
        ->toBe($first['doctor']->id);

    expect($firstResult->medicalService->id)
        ->toBe($first['clinicService']->id);

    expect($secondResult->clinic->id)
        ->toBe($second['clinic']->id);

    expect($secondResult->doctor->id)
        ->toBe($second['doctor']->id);

    expect($secondResult->medicalService->id)
        ->toBe($second['clinicService']->id);

    expect($thirdResult->clinic->id)
        ->toBe($third['clinic']->id);

    expect($thirdResult->doctor->id)
        ->toBe($third['doctor']->id);

    expect($thirdResult->medicalService->id)
        ->toBe($third['clinicService']->id);
});

/*
|--------------------------------------------------------------------------
| No N+1 query
|--------------------------------------------------------------------------
*/

it('does not execute N+1 queries when accessing relationships', function () {
    createDoctorServicePrice();
    createDoctorServicePrice();
    createDoctorServicePrice();
    createDoctorServicePrice();
    createDoctorServicePrice();

    DB::enableQueryLog();

    $result = $this->service->getAllDoctorServices();

    /*
     * Access all relationships after the query.
     * If eager loading is missing, this would generate
     * additional queries for every record.
     */
    foreach ($result as $item) {
        $item->clinic;
        $item->doctor;
        $item->medicalService;
    }

    $queries = DB::getQueryLog();

    DB::disableQueryLog();

    /*
     * Expected:
     *
     * 1 query for doctor_service_prices
     * 1 query for clinics
     * 1 query for doctors
     * 1 query for medicalServices
     *
     * Total = 4 queries.
     */

    expect(count($queries))
        ->toBe(4);
});

/*
|--------------------------------------------------------------------------
| Query count remains constant with more records
|--------------------------------------------------------------------------
*/

it('keeps query count constant regardless of number of records', function () {
    createDoctorServicePrice();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $result = $this->service->getAllDoctorServices();

    foreach ($result as $item) {
        $item->clinic;
        $item->doctor;
        $item->medicalService;
    }

    $queriesForOneRecord = count(DB::getQueryLog());

    DB::disableQueryLog();


    createDoctorServicePrice();
    createDoctorServicePrice();
    createDoctorServicePrice();
    createDoctorServicePrice();


    DB::flushQueryLog();
    DB::enableQueryLog();

    $result = $this->service->getAllDoctorServices();

    foreach ($result as $item) {
        $item->clinic;
        $item->doctor;
        $item->medicalService;
    }

    $queriesForFiveRecords = count(DB::getQueryLog());

    DB::disableQueryLog();


    expect($queriesForOneRecord)
        ->toBe(4);

    expect($queriesForFiveRecords)
        ->toBe(4);
});

/*
|--------------------------------------------------------------------------
| Doesn't mutate database
|--------------------------------------------------------------------------
*/

it('does not modify the database', function () {
    $first = createDoctorServicePrice();
    $second = createDoctorServicePrice();

    $beforeCount = Clinic_doctor_medicalService::count();

    $this->service->getAllDoctorServices();

    expect(Clinic_doctor_medicalService::count())
        ->toBe($beforeCount);

    expect(
        Clinic_doctor_medicalService::whereKey(
            $first['medicalServicePrice']->id
        )->exists()
    )->toBeTrue();

    expect(
        Clinic_doctor_medicalService::whereKey(
            $second['medicalServicePrice']->id
        )->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Does not duplicate records
|--------------------------------------------------------------------------
*/

it('returns each database record exactly once', function () {
    $first = createDoctorServicePrice();
    $second = createDoctorServicePrice();
    $third = createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    $ids = $result->pluck('id');

    expect($ids)
        ->toHaveCount(3)
        ->and($ids->unique()->count())
        ->toBe(3);

    expect($ids->contains($first['medicalServicePrice']->id))
        ->toBeTrue();

    expect($ids->contains($second['medicalServicePrice']->id))
        ->toBeTrue();

    expect($ids->contains($third['medicalServicePrice']->id))
        ->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Multiple records with same clinic
|--------------------------------------------------------------------------
*/

it('correctly handles multiple service prices belonging to the same clinic', function () {
    $clinic = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create();
    $doctorTwo = Doctor::factory()->create();

    $clinicServiceOne = DoctorService::factory()->create();
    $clinicServiceTwo = DoctorService::factory()->create();

    $first = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorOne->id,
        'medicalService_id' => $clinicServiceOne->id,
    ]);

    $second = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorTwo->id,
        'medicalService_id' => $clinicServiceTwo->id,
    ]);

    $result = $this->service->getAllDoctorServices();

    expect($result)
        ->toHaveCount(2);

    $firstResult = $result->firstWhere('id', $first->id);
    $secondResult = $result->firstWhere('id', $second->id);

    expect($firstResult->clinic->id)
        ->toBe($clinic->id);

    expect($secondResult->clinic->id)
        ->toBe($clinic->id);

    expect($firstResult->doctor->id)
        ->toBe($doctorOne->id);

    expect($secondResult->doctor->id)
        ->toBe($doctorTwo->id);

    expect($firstResult->medicalService->id)
        ->toBe($clinicServiceOne->id);

    expect($secondResult->medicalService->id)
        ->toBe($clinicServiceTwo->id);
});

/*
|--------------------------------------------------------------------------
| Multiple records with same doctor
|--------------------------------------------------------------------------
*/

it('correctly handles multiple service prices belonging to the same doctor', function () {
    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $serviceOne = DoctorService::factory()->create();
    $serviceTwo = DoctorService::factory()->create();

    $first = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinicOne->id,
        'doctor_id' => $doctor->id,
        'medicalService_id' => $serviceOne->id,
    ]);

    $second = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinicTwo->id,
        'doctor_id' => $doctor->id,
        'medicalService_id' => $serviceTwo->id,
    ]);

    $result = $this->service->getAllDoctorServices();

    expect($result)
        ->toHaveCount(2);

    expect(
        $result->firstWhere('id', $first->id)->doctor->id
    )->toBe($doctor->id);

    expect(
        $result->firstWhere('id', $second->id)->doctor->id
    )->toBe($doctor->id);
});

/*
|--------------------------------------------------------------------------
| Independent relationships
|--------------------------------------------------------------------------
*/

it('does not mix relationships between records', function () {
    $first = createDoctorServicePrice();
    $second = createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    $firstResult = $result->firstWhere(
        'id',
        $first['medicalServicePrice']->id
    );

    $secondResult = $result->firstWhere(
        'id',
        $second['medicalServicePrice']->id
    );

    expect($firstResult->clinic->id)
        ->toBe($first['clinic']->id)
        ->not->toBe($second['clinic']->id);

    expect($firstResult->doctor->id)
        ->toBe($first['doctor']->id)
        ->not->toBe($second['doctor']->id);

    expect($firstResult->medicalService->id)
        ->toBe($first['clinicService']->id)
        ->not->toBe($second['clinicService']->id);

    expect($secondResult->clinic->id)
        ->toBe($second['clinic']->id)
        ->not->toBe($first['clinic']->id);

    expect($secondResult->doctor->id)
        ->toBe($second['doctor']->id)
        ->not->toBe($first['doctor']->id);

    expect($secondResult->medicalService->id)
        ->toBe($second['clinicService']->id)
        ->not->toBe($first['clinicService']->id);
});

/*
|--------------------------------------------------------------------------
| Relation instances are loaded
|--------------------------------------------------------------------------
*/

it('contains loaded relation models rather than only foreign keys', function () {
    $context = createDoctorServicePrice();

    $result = $this->service->getAllDoctorServices();

    $item = $result->firstWhere(
        'id',
        $context['medicalServicePrice']->id
    );

    expect($item->clinic)
        ->toBeInstanceOf(Clinic::class);

    expect($item->doctor)
        ->toBeInstanceOf(Doctor::class);

    expect($item->medicalService)
        ->toBeInstanceOf(DoctorService::class);
});

/*
|--------------------------------------------------------------------------
| Calling method twice
|--------------------------------------------------------------------------
*/

it('can retrieve clinic services multiple times consistently', function () {
    createDoctorServicePrice();
    createDoctorServicePrice();

    $firstResult = $this->service->getAllDoctorServices();
    $secondResult = $this->service->getAllDoctorServices();

    expect($firstResult->count())
        ->toBe($secondResult->count());

    expect(
        $firstResult->pluck('id')->sort()->values()->all()
    )->toBe(
        $secondResult->pluck('id')->sort()->values()->all()
    );
});
