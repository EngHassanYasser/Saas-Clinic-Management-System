<?php

use App\Models\Clinic;
use App\Models\ClinicService;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use App\Services\ServiceCatalog\ClinicServicePriceService;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->service = app(ClinicServicePriceService::class);
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

    $clinicService = ClinicService::factory()->create();

    $doctorServicePrice = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicService->id,
    ]);

    return [
        'clinic' => $clinic,
        'doctor' => $doctor,
        'clinicService' => $clinicService,
        'doctorServicePrice' => $doctorServicePrice,
    ];
}
/*
|--------------------------------------------------------------------------
| Return type
|--------------------------------------------------------------------------
*/

it('returns a collection', function () {
    createDoctorServicePrice();

    $result = $this->service->getAllClinicServices();

    expect($result)
        ->toBeInstanceOf(Collection::class);
});

/*
|--------------------------------------------------------------------------
| Empty database
|--------------------------------------------------------------------------
*/

it('returns an empty collection when there are no clinic services', function () {

    $result = $this->service->getAllClinicServices();

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

    $result = $this->service->getAllClinicServices();

    expect($result)
        ->toHaveCount(3);

    expect($result->pluck('id')->sort()->values()->all())
        ->toBe(
            collect([
                $first['doctorServicePrice']->id,
                $second['doctorServicePrice']->id,
                $third['doctorServicePrice']->id,
            ])->sort()->values()->all()
        );
});

/*
|--------------------------------------------------------------------------
| Correct model
|--------------------------------------------------------------------------
*/

it('returns Doctor_service_price models', function () {
    createDoctorServicePrice();

    $result = $this->service->getAllClinicServices();

    expect($result->every(
        fn (Doctor_service_price $item) => $item instanceof Doctor_service_price
    ))->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Database persistence
|--------------------------------------------------------------------------
*/

it('returns records that actually exist in the database', function () {
    $context = createDoctorServicePrice();

    $result = $this->service->getAllClinicServices();

    expect(
        $result->contains(
            fn (Doctor_service_price $item) => $item->id === $context['doctorServicePrice']->id
        )
    )->toBeTrue();

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $context['doctorServicePrice']->id)
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

    $result = $this->service->getAllClinicServices();

    $item = $result->firstWhere(
        'id',
        $context['doctorServicePrice']->id
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

    $result = $this->service->getAllClinicServices();

    $item = $result->firstWhere(
        'id',
        $context['doctorServicePrice']->id
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

it('eager loads the clinic_service relationship', function () {
    $context = createDoctorServicePrice();

    $result = $this->service->getAllClinicServices();

    $item = $result->firstWhere(
        'id',
        $context['doctorServicePrice']->id
    );

    expect($item->relationLoaded('clinic_service'))
        ->toBeTrue();

    expect($item->clinic_service)
        ->not->toBeNull()
        ->toBeInstanceOf(ClinicService::class)
        ->and($item->clinic_service->id)
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

    $result = $this->service->getAllClinicServices();

    expect($result)->toHaveCount(3);

    foreach ($result as $item) {
        expect($item->relationLoaded('clinic'))
            ->toBeTrue();

        expect($item->relationLoaded('doctor'))
            ->toBeTrue();

        expect($item->relationLoaded('clinic_service'))
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

    $result = $this->service->getAllClinicServices();

    $firstResult = $result->firstWhere(
        'id',
        $first['doctorServicePrice']->id
    );

    $secondResult = $result->firstWhere(
        'id',
        $second['doctorServicePrice']->id
    );

    $thirdResult = $result->firstWhere(
        'id',
        $third['doctorServicePrice']->id
    );

    expect($firstResult->clinic->id)
        ->toBe($first['clinic']->id);

    expect($firstResult->doctor->id)
        ->toBe($first['doctor']->id);

    expect($firstResult->clinic_service->id)
        ->toBe($first['clinicService']->id);

    expect($secondResult->clinic->id)
        ->toBe($second['clinic']->id);

    expect($secondResult->doctor->id)
        ->toBe($second['doctor']->id);

    expect($secondResult->clinic_service->id)
        ->toBe($second['clinicService']->id);

    expect($thirdResult->clinic->id)
        ->toBe($third['clinic']->id);

    expect($thirdResult->doctor->id)
        ->toBe($third['doctor']->id);

    expect($thirdResult->clinic_service->id)
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

    $result = $this->service->getAllClinicServices();

    /*
     * Access all relationships after the query.
     * If eager loading is missing, this would generate
     * additional queries for every record.
     */
    foreach ($result as $item) {
        $item->clinic;
        $item->doctor;
        $item->clinic_service;
    }

    $queries = DB::getQueryLog();

    DB::disableQueryLog();

    /*
     * Expected:
     *
     * 1 query for doctor_service_prices
     * 1 query for clinics
     * 1 query for doctors
     * 1 query for clinic_services
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

    $result = $this->service->getAllClinicServices();

    foreach ($result as $item) {
        $item->clinic;
        $item->doctor;
        $item->clinic_service;
    }

    $queriesForOneRecord = count(DB::getQueryLog());

    DB::disableQueryLog();


    createDoctorServicePrice();
    createDoctorServicePrice();
    createDoctorServicePrice();
    createDoctorServicePrice();


    DB::flushQueryLog();
    DB::enableQueryLog();

    $result = $this->service->getAllClinicServices();

    foreach ($result as $item) {
        $item->clinic;
        $item->doctor;
        $item->clinic_service;
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

    $beforeCount = Doctor_service_price::count();

    $this->service->getAllClinicServices();

    expect(Doctor_service_price::count())
        ->toBe($beforeCount);

    expect(
        Doctor_service_price::whereKey(
            $first['doctorServicePrice']->id
        )->exists()
    )->toBeTrue();

    expect(
        Doctor_service_price::whereKey(
            $second['doctorServicePrice']->id
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

    $result = $this->service->getAllClinicServices();

    $ids = $result->pluck('id');

    expect($ids)
        ->toHaveCount(3)
        ->and($ids->unique()->count())
        ->toBe(3);

    expect($ids->contains($first['doctorServicePrice']->id))
        ->toBeTrue();

    expect($ids->contains($second['doctorServicePrice']->id))
        ->toBeTrue();

    expect($ids->contains($third['doctorServicePrice']->id))
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

    $clinicServiceOne = ClinicService::factory()->create();
    $clinicServiceTwo = ClinicService::factory()->create();

    $first = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorOne->id,
        'clinic_service_id' => $clinicServiceOne->id,
    ]);

    $second = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorTwo->id,
        'clinic_service_id' => $clinicServiceTwo->id,
    ]);

    $result = $this->service->getAllClinicServices();

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

    expect($firstResult->clinic_service->id)
        ->toBe($clinicServiceOne->id);

    expect($secondResult->clinic_service->id)
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

    $serviceOne = ClinicService::factory()->create();
    $serviceTwo = ClinicService::factory()->create();

    $first = Doctor_service_price::factory()->create([
        'clinic_id' => $clinicOne->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $serviceOne->id,
    ]);

    $second = Doctor_service_price::factory()->create([
        'clinic_id' => $clinicTwo->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $serviceTwo->id,
    ]);

    $result = $this->service->getAllClinicServices();

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

    $result = $this->service->getAllClinicServices();

    $firstResult = $result->firstWhere(
        'id',
        $first['doctorServicePrice']->id
    );

    $secondResult = $result->firstWhere(
        'id',
        $second['doctorServicePrice']->id
    );

    expect($firstResult->clinic->id)
        ->toBe($first['clinic']->id)
        ->not->toBe($second['clinic']->id);

    expect($firstResult->doctor->id)
        ->toBe($first['doctor']->id)
        ->not->toBe($second['doctor']->id);

    expect($firstResult->clinic_service->id)
        ->toBe($first['clinicService']->id)
        ->not->toBe($second['clinicService']->id);

    expect($secondResult->clinic->id)
        ->toBe($second['clinic']->id)
        ->not->toBe($first['clinic']->id);

    expect($secondResult->doctor->id)
        ->toBe($second['doctor']->id)
        ->not->toBe($first['doctor']->id);

    expect($secondResult->clinic_service->id)
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

    $result = $this->service->getAllClinicServices();

    $item = $result->firstWhere(
        'id',
        $context['doctorServicePrice']->id
    );

    expect($item->clinic)
        ->toBeInstanceOf(Clinic::class);

    expect($item->doctor)
        ->toBeInstanceOf(Doctor::class);

    expect($item->clinic_service)
        ->toBeInstanceOf(ClinicService::class);
});

/*
|--------------------------------------------------------------------------
| Calling method twice
|--------------------------------------------------------------------------
*/

it('can retrieve clinic services multiple times consistently', function () {
    createDoctorServicePrice();
    createDoctorServicePrice();

    $firstResult = $this->service->getAllClinicServices();
    $secondResult = $this->service->getAllClinicServices();

    expect($firstResult->count())
        ->toBe($secondResult->count());

    expect(
        $firstResult->pluck('id')->sort()->values()->all()
    )->toBe(
        $secondResult->pluck('id')->sort()->values()->all()
    );
});
