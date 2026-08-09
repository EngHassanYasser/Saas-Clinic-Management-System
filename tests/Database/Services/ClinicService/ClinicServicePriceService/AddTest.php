<?php

use App\Models\Clinic;
use App\Models\DoctorService;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use App\Services\ServiceCatalog\DoctorServicePriceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(DoctorServicePriceService::class);
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function makeAddDoctorServicePriceContext(): array
{
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinicService = DoctorService::factory()->create();

    $data = [
        'doctor_id' => $doctor->id,
        'doctorService_id' => $clinicService->id,
        'price' => 250.50,
        'description' => 'Initial consultation',
    ];

    return [
        'clinic' => $clinic,
        'doctor' => $doctor,
        'clinicService' => $clinicService,
        'data' => $data,
    ];
}

/*
|--------------------------------------------------------------------------
| Return type
|--------------------------------------------------------------------------
*/

it('returns a Doctor_service_price model', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    expect($result)
        ->toBeInstanceOf(Doctor_service_price::class);
});

/*
|--------------------------------------------------------------------------
| Creates database record
|--------------------------------------------------------------------------
*/

it('creates a doctor service price in the database', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $result->id)
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Correct clinic
|--------------------------------------------------------------------------
*/

it('stores the provided clinic id', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    expect($result->clinic_id)
        ->toBe($context['clinic']->id);

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $result->id)
            ->value('clinic_id')
    )->toBe($context['clinic']->id);
});

/*
|--------------------------------------------------------------------------
| Correct doctor
|--------------------------------------------------------------------------
*/

it('stores the provided doctor id', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    expect($result->doctor_id)
        ->toBe($context['doctor']->id);

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $result->id)
            ->value('doctor_id')
    )->toBe($context['doctor']->id);
});

/*
|--------------------------------------------------------------------------
| Correct clinic service
|--------------------------------------------------------------------------
*/

it('stores the provided clinic service id', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    expect($result->doctorService_id)
        ->toBe($context['clinicService']->id);

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $result->id)
            ->value('doctorService_id')
    )->toBe($context['clinicService']->id);
});

/*
|--------------------------------------------------------------------------
| Correct price
|--------------------------------------------------------------------------
*/

it('stores the provided price', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    expect((float) $result->price)
        ->toBe(250.50);

    expect(
        (float) DB::table('doctor_service_prices')
            ->where('id', $result->id)
            ->value('price')
    )->toBe(250.50);
});

/*
|--------------------------------------------------------------------------
| Correct description
|--------------------------------------------------------------------------
*/

it('stores the provided description', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    expect($result->description)
        ->toBe($context['data']['description']);

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $result->id)
            ->value('description')
    )->toBe($context['data']['description']);
});

/*
|--------------------------------------------------------------------------
| All fields together
|--------------------------------------------------------------------------
*/

it('stores all provided fields correctly', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    $row = DB::table('doctor_service_prices')
        ->where('id', $result->id)
        ->first();

    expect($row->clinic_id)
        ->toBe($context['clinic']->id);

    expect($row->doctor_id)
        ->toBe($context['data']['doctor_id']);

    expect($row->doctorService_id)
        ->toBe($context['data']['doctorService_id']);

    expect((float) $row->price)
        ->toBe(250.50);

    expect($row->description)
        ->toBe($context['data']['description']);
});

/*
|--------------------------------------------------------------------------
| Clinic id must come from method argument
|--------------------------------------------------------------------------
*/

it('uses the clinic id argument instead of expecting clinic_id from data', function () {
    $context = makeAddDoctorServicePriceContext();

    $anotherClinic = Clinic::factory()->create();

    $data = $context['data'];

    /*
     * Even if clinic_id exists in the input,
     * the method must use the explicit $clinicId argument.
     */
    $data['clinic_id'] = $anotherClinic->id;

    $result = $this->service->add(
        $data,
        $context['clinic']->id
    );

    expect($result->clinic_id)
        ->toBe($context['clinic']->id)
        ->not->toBe($anotherClinic->id);
});

/*
|--------------------------------------------------------------------------
| Input data is not modified
|--------------------------------------------------------------------------
*/

it('does not modify the input data', function () {
    $context = makeAddDoctorServicePriceContext();

    $data = $context['data'];

    $originalData = $data;

    $this->service->add(
        $data,
        $context['clinic']->id
    );

    expect($data)
        ->toBe($originalData);
});

/*
|--------------------------------------------------------------------------
| Correct model id
|--------------------------------------------------------------------------
*/

it('returns the exact database record that was created', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    $databaseRecord = Doctor_service_price::find($result->id);

    expect($databaseRecord)
        ->not->toBeNull();

    expect($databaseRecord->id)
        ->toBe($result->id);
});

/*
|--------------------------------------------------------------------------
| Only one record is created
|--------------------------------------------------------------------------
*/

it('creates exactly one record', function () {
    $context = makeAddDoctorServicePriceContext();

    $before = Doctor_service_price::count();

    $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    $after = Doctor_service_price::count();

    expect($after)
        ->toBe($before + 1);
});

/*
|--------------------------------------------------------------------------
| Multiple calls create independent records
|--------------------------------------------------------------------------
*/

it('creates independent records when called multiple times', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $firstDoctorService = DoctorService::factory()->create();
    $secondDoctorService = DoctorService::factory()->create();

    $firstData = [
        'doctor_id' => $doctor->id,
        'doctorService_id' => $firstDoctorService->id,
        'price' => 100.00,
        'description' => 'First price',
    ];

    $secondData = [
        'doctor_id' => $doctor->id,
        'doctorService_id' => $secondDoctorService->id,
        'price' => 200.00,
        'description' => 'Second price',
    ];

    $first = $this->service->add(
        $firstData,
        $clinic->id
    );

    $second = $this->service->add(
        $secondData,
        $clinic->id
    );

    expect($first->id)
        ->not->toBe($second->id);

    expect($first->clinic_id)
        ->toBe($clinic->id);

    expect($second->clinic_id)
        ->toBe($clinic->id);

    expect($first->doctorService_id)
        ->toBe($firstDoctorService->id);

    expect($second->doctorService_id)
        ->toBe($secondDoctorService->id);

    expect((float) $first->price)
        ->toBe(100.00);

    expect((float) $second->price)
        ->toBe(200.00);
});
/*
|--------------------------------------------------------------------------
| Existing records remain unchanged
|--------------------------------------------------------------------------
*/

it('does not modify existing doctor service prices', function () {
    $existing = makeAddDoctorServicePriceContext();

    $existingRecord = Doctor_service_price::factory()->create([
        'clinic_id' => $existing['clinic']->id,
        'doctor_id' => $existing['doctor']->id,
        'doctorService_id' => $existing['clinicService']->id,
        'price' => 150.00,
        'description' => 'Existing price',
    ]);

    $anotherDoctorService = DoctorService::factory()->create();

    $newData = [
        'doctor_id' => $existing['doctor']->id,
        'doctorService_id' => $anotherDoctorService->id,
        'price' => 300.00,
        'description' => 'New price',
    ];

    $this->service->add(
        $newData,
        $existing['clinic']->id
    );

    $existingRecord->refresh();

    expect((float) $existingRecord->price)
        ->toBe(150.00);

    expect($existingRecord->description)
        ->toBe('Existing price');

    expect($existingRecord->clinic_id)
        ->toBe($existing['clinic']->id);

    expect($existingRecord->doctor_id)
        ->toBe($existing['doctor']->id);

    expect($existingRecord->doctorService_id)
        ->toBe($existing['clinicService']->id);
});

/*
|--------------------------------------------------------------------------
| Relationships exist
|--------------------------------------------------------------------------
*/

it('creates a record with valid clinic relationship', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    $result->load('clinic');

    expect($result->clinic)
        ->toBeInstanceOf(Clinic::class);

    expect($result->clinic->id)
        ->toBe($context['clinic']->id);
});

/*
|--------------------------------------------------------------------------
| Doctor relationship
|--------------------------------------------------------------------------
*/

it('creates a record with valid doctor relationship', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    $result->load('doctor');

    expect($result->doctor)
        ->toBeInstanceOf(Doctor::class);

    expect($result->doctor->id)
        ->toBe($context['doctor']->id);
});

/*
|--------------------------------------------------------------------------
| Clinic service relationship
|--------------------------------------------------------------------------
*/

it('creates a record with valid clinic service relationship', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    $result->load('doctorService');

    expect($result->doctorService)
        ->toBeInstanceOf(DoctorService::class);

    expect($result->doctorService->id)
        ->toBe($context['clinicService']->id);
});

/*
|--------------------------------------------------------------------------
| Decimal price values
|--------------------------------------------------------------------------
*/

it('stores decimal prices accurately', function () {
    $context = makeAddDoctorServicePriceContext();

    $data = $context['data'];

    $data['price'] = 1234.75;

    $result = $this->service->add(
        $data,
        $context['clinic']->id
    );

    $result->refresh();

    expect((float) $result->price)
        ->toBe(1234.75);

    expect(
        (float) DB::table('doctor_service_prices')
            ->where('id', $result->id)
            ->value('price')
    )->toBe(1234.75);
});

/*
|--------------------------------------------------------------------------
| Zero price
|--------------------------------------------------------------------------
*/

it('stores zero price correctly', function () {
    $context = makeAddDoctorServicePriceContext();

    $data = $context['data'];

    $data['price'] = 0;

    $result = $this->service->add(
        $data,
        $context['clinic']->id
    );

    $result->refresh();

    expect((float) $result->price)
        ->toBe(0.0);
});

/*
|--------------------------------------------------------------------------
| Null description
|--------------------------------------------------------------------------
*/

it('rejects a null description', function () {
    $context = makeAddDoctorServicePriceContext();

    $data = $context['data'];
    $data['description'] = null;

    expect(fn () => $this->service->add(
        $data,
        $context['clinic']->id
    ))->toThrow(\Illuminate\Database\QueryException::class);
});

/*
|--------------------------------------------------------------------------
| Empty description
|--------------------------------------------------------------------------
*/

it('stores an empty description when provided', function () {
    $context = makeAddDoctorServicePriceContext();

    $data = $context['data'];

    $data['description'] = '';

    $result = $this->service->add(
        $data,
        $context['clinic']->id
    );

    $result->refresh();

    expect($result->description)
        ->toBe('');
});

/*
|--------------------------------------------------------------------------
| Different doctors
|--------------------------------------------------------------------------
*/

it('can create prices for different doctors', function () {
    $clinic = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create();
    $doctorTwo = Doctor::factory()->create();

    $clinicService = DoctorService::factory()->create();

    $first = $this->service->add([
        'doctor_id' => $doctorOne->id,
        'doctorService_id' => $clinicService->id,
        'price' => 100,
        'description' => 'Doctor one',
    ], $clinic->id);

    $second = $this->service->add([
        'doctor_id' => $doctorTwo->id,
        'doctorService_id' => $clinicService->id,
        'price' => 200,
        'description' => 'Doctor two',
    ], $clinic->id);

    expect($first->doctor_id)
        ->toBe($doctorOne->id);

    expect($second->doctor_id)
        ->toBe($doctorTwo->id);

    expect($first->id)
        ->not->toBe($second->id);
});

/*
|--------------------------------------------------------------------------
| Different clinic services
|--------------------------------------------------------------------------
*/

it('can create prices for different clinic services', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $serviceOne = DoctorService::factory()->create();
    $serviceTwo = DoctorService::factory()->create();

    $first = $this->service->add([
        'doctor_id' => $doctor->id,
        'doctorService_id' => $serviceOne->id,
        'price' => 100,
        'description' => 'Service one',
    ], $clinic->id);

    $second = $this->service->add([
        'doctor_id' => $doctor->id,
        'doctorService_id' => $serviceTwo->id,
        'price' => 200,
        'description' => 'Service two',
    ], $clinic->id);

    expect($first->doctorService_id)
        ->toBe($serviceOne->id);

    expect($second->doctorService_id)
        ->toBe($serviceTwo->id);

    expect($first->id)
        ->not->toBe($second->id);
});

/*
|--------------------------------------------------------------------------
| Different clinics
|--------------------------------------------------------------------------
*/

it('stores each record under the correct clinic', function () {
    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();
    $clinicService = DoctorService::factory()->create();

    $first = $this->service->add([
        'doctor_id' => $doctor->id,
        'doctorService_id' => $clinicService->id,
        'price' => 100,
        'description' => 'Clinic one',
    ], $clinicOne->id);

    $second = $this->service->add([
        'doctor_id' => $doctor->id,
        'doctorService_id' => $clinicService->id,
        'price' => 200,
        'description' => 'Clinic two',
    ], $clinicTwo->id);

    expect($first->clinic_id)
        ->toBe($clinicOne->id)
        ->not->toBe($clinicTwo->id);

    expect($second->clinic_id)
        ->toBe($clinicTwo->id)
        ->not->toBe($clinicOne->id);
});

/*
|--------------------------------------------------------------------------
| Database row count
|--------------------------------------------------------------------------
*/

it('increases database count by exactly one after adding a price', function () {
    $context = makeAddDoctorServicePriceContext();

    $before = DB::table('doctor_service_prices')->count();

    $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    $after = DB::table('doctor_service_prices')->count();

    expect($after)
        ->toBe($before + 1);
});

/*
|--------------------------------------------------------------------------
| Returned model is persisted
|--------------------------------------------------------------------------
*/

it('returns a persisted model', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    expect($result->exists)
        ->toBeTrue();

    expect($result->wasRecentlyCreated)
        ->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Database and returned model match
|--------------------------------------------------------------------------
*/

it('returns values that match the persisted database record', function () {
    $context = makeAddDoctorServicePriceContext();

    $result = $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    $databaseRecord = Doctor_service_price::findOrFail($result->id);

    expect($databaseRecord->clinic_id)
        ->toBe($result->clinic_id);

    expect($databaseRecord->doctor_id)
        ->toBe($result->doctor_id);

    expect($databaseRecord->doctorService_id)
        ->toBe($result->doctorService_id);

    expect((float) $databaseRecord->price)
        ->toBe((float) $result->price);

    expect($databaseRecord->description)
        ->toBe($result->description);
});
it('cannot create duplicate doctor service price for the same clinic, doctor and clinic service', function () {
    $context = makeAddDoctorServicePriceContext();

    $this->service->add(
        $context['data'],
        $context['clinic']->id
    );

    expect(fn () => $this->service->add(
        $context['data'],
        $context['clinic']->id
    ))->toThrow(\Illuminate\Database\QueryException::class);
});