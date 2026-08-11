<?php

use App\Models\Clinic;
use App\Models\DoctorService;
use App\Models\Doctor;
use App\Models\Clinic_doctor_medical_service;
use App\Services\ServiceCatalog\DoctorServicePriceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(DoctorServicePriceService::class);
});


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function makeUpdateDoctorServicePriceContext(): array
{
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinicService = DoctorService::factory()->create();

    $medicalServicePrice = Clinic_doctor_medical_service::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'medicalService_id' => $clinicService->id,
        'price' => 150.00,
        'description' => 'Original description',
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
| Return value
|--------------------------------------------------------------------------
*/

it('returns a boolean result', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $result = $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 250.00,
        'description' => 'Updated description',
    ], $context['clinic']->id);

    expect($result)
        ->toBeBool();
});


/*
|--------------------------------------------------------------------------
| Successful update
|--------------------------------------------------------------------------
*/

it('updates the doctor service price successfully', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $result = $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 250.00,
        'description' => 'Updated description',
    ], $context['clinic']->id);

    expect($result)
        ->toBeTrue();

    $record = Clinic_doctor_medical_service::findOrFail(
        $context['medicalServicePrice']->id
    );

    expect($record->price)
        ->toEqual(250.00);

    expect($record->description)
        ->toBe('Updated description');
});


/*
|--------------------------------------------------------------------------
| Database persistence
|--------------------------------------------------------------------------
*/

it('persists updated values in the database', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 325.75,
        'description' => 'Database updated value',
    ], $context['clinic']->id);

    $row = DB::table('doctor_service_prices')
        ->where('id', $context['medicalServicePrice']->id)
        ->first();

    expect($row)
        ->not->toBeNull();

    expect($row->clinic_id)
        ->toBe($context['clinic']->id);

    expect($row->doctor_id)
        ->toBe($context['doctor']->id);

    expect($row->medicalService_id)
        ->toBe($context['clinicService']->id);

    expect((float) $row->price)
        ->toBe(325.75);

    expect($row->description)
        ->toBe('Database updated value');
});


/*
|--------------------------------------------------------------------------
| Clinic ID comes from method argument
|--------------------------------------------------------------------------
*/

it('uses the clinic id argument instead of clinic_id from data', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $anotherClinic = Clinic::factory()->create();

    $data = [
        'id' => $context['medicalServicePrice']->id,
        'clinic_id' => $anotherClinic->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 275.00,
        'description' => 'Updated description',
    ];

    $this->service->update(
        $data,
        $context['clinic']->id
    );

    $record = Clinic_doctor_medical_service::findOrFail(
        $context['medicalServicePrice']->id
    );

    expect($record->clinic_id)
        ->toBe($context['clinic']->id)
        ->not->toBe($anotherClinic->id);
});


/*
|--------------------------------------------------------------------------
| Doctor ID
|--------------------------------------------------------------------------
*/

it('updates the doctor id correctly', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $anotherDoctor = Doctor::factory()->create();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $anotherDoctor->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => 'Changed doctor',
    ], $context['clinic']->id);

    $record = Clinic_doctor_medical_service::findOrFail(
        $context['medicalServicePrice']->id
    );

    expect($record->doctor_id)
        ->toBe($anotherDoctor->id);
});


/*
|--------------------------------------------------------------------------
| Clinic service ID
|--------------------------------------------------------------------------
*/

it('updates the clinic service id correctly', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $anotherDoctorService = DoctorService::factory()->create();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $anotherDoctorService->id,
        'price' => 200.00,
        'description' => 'Changed clinic service',
    ], $context['clinic']->id);

    $record = Clinic_doctor_medical_service::findOrFail(
        $context['medicalServicePrice']->id
    );

    expect($record->medicalService_id)
        ->toBe($anotherDoctorService->id);
});


/*
|--------------------------------------------------------------------------
| Price
|--------------------------------------------------------------------------
*/

it('updates the price correctly', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 999.99,
        'description' => 'Updated price',
    ], $context['clinic']->id);

    $record = Clinic_doctor_medical_service::findOrFail(
        $context['medicalServicePrice']->id
    );

    expect((float) $record->price)
        ->toBe(999.99);
});


/*
|--------------------------------------------------------------------------
| Description
|--------------------------------------------------------------------------
*/

it('updates the description correctly', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => 'Completely new description',
    ], $context['clinic']->id);

    $record = Clinic_doctor_medical_service::findOrFail(
        $context['medicalServicePrice']->id
    );

    expect($record->description)
        ->toBe('Completely new description');
});


/*
|--------------------------------------------------------------------------
| All fields together
|--------------------------------------------------------------------------
*/

it('updates all editable fields together', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $anotherDoctor = Doctor::factory()->create();
    $anotherDoctorService = DoctorService::factory()->create();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $anotherDoctor->id,
        'medicalService_id' => $anotherDoctorService->id,
        'price' => 450.75,
        'description' => 'All fields updated',
    ], $context['clinic']->id);

    $record = Clinic_doctor_medical_service::findOrFail(
        $context['medicalServicePrice']->id
    );

    expect($record->clinic_id)
        ->toBe($context['clinic']->id);

    expect($record->doctor_id)
        ->toBe($anotherDoctor->id);

    expect($record->medicalService_id)
        ->toBe($anotherDoctorService->id);

    expect((float) $record->price)
        ->toBe(450.75);

    expect($record->description)
        ->toBe('All fields updated');
});


/*
|--------------------------------------------------------------------------
| Existing values are replaced
|--------------------------------------------------------------------------
*/

it('replaces the old values instead of keeping them', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $anotherDoctor = Doctor::factory()->create();
    $anotherDoctorService = DoctorService::factory()->create();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $anotherDoctor->id,
        'medicalService_id' => $anotherDoctorService->id,
        'price' => 800.00,
        'description' => 'New description',
    ], $context['clinic']->id);

    $record = Clinic_doctor_medical_service::findOrFail(
        $context['medicalServicePrice']->id
    );

    expect($record->doctor_id)
        ->not->toBe($context['doctor']->id);

    expect($record->medicalService_id)
        ->not->toBe($context['clinicService']->id);

    expect((float) $record->price)
        ->not->toBe(150.00);

    expect($record->description)
        ->not->toBe('Original description');
});


/*
|--------------------------------------------------------------------------
| Only target record is updated
|--------------------------------------------------------------------------
*/

it('updates only the requested record', function () {
    $clinic = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create();
    $doctorTwo = Doctor::factory()->create();

    $serviceOne = DoctorService::factory()->create();
    $serviceTwo = DoctorService::factory()->create();

    $first = Clinic_doctor_medical_service::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorOne->id,
        'medicalService_id' => $serviceOne->id,
        'price' => 100.00,
        'description' => 'First',
    ]);

    $second = Clinic_doctor_medical_service::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorTwo->id,
        'medicalService_id' => $serviceTwo->id,
        'price' => 200.00,
        'description' => 'Second',
    ]);

    $this->service->update([
        'id' => $first->id,
        'doctor_id' => $doctorOne->id,
        'medicalService_id' => $serviceOne->id,
        'price' => 999.00,
        'description' => 'Updated first',
    ], $clinic->id);

    $first->refresh();
    $second->refresh();

    expect((float) $first->price)
        ->toBe(999.00);

    expect($first->description)
        ->toBe('Updated first');

    expect((float) $second->price)
        ->toBe(200.00);

    expect($second->description)
        ->toBe('Second');
});


/*
|--------------------------------------------------------------------------
| ID is used to locate the record
|--------------------------------------------------------------------------
*/

it('updates the record matching the provided id', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 333.00,
        'description' => 'Found by id',
    ], $context['clinic']->id);

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $context['medicalServicePrice']->id)
            ->value('price')
    )->toEqual(333.00);
});


/*
|--------------------------------------------------------------------------
| Non-existing ID
|--------------------------------------------------------------------------
*/

it('returns false when the requested record does not exist', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $nonExistingId =
        Clinic_doctor_medical_service::max('id') + 999;

    $result = $this->service->update([
        'id' => $nonExistingId,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 500.00,
        'description' => 'Should not exist',
    ], $context['clinic']->id);

    expect($result)
        ->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Non-existing ID does not create a record
|--------------------------------------------------------------------------
*/

it('does not create a new record when the id does not exist', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $beforeCount = Clinic_doctor_medical_service::count();

    $nonExistingId =
        Clinic_doctor_medical_service::max('id') + 999;

    $this->service->update([
        'id' => $nonExistingId,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 500.00,
        'description' => 'Should not exist',
    ], $context['clinic']->id);

    expect(Clinic_doctor_medical_service::count())
        ->toBe($beforeCount);
});


/*
|--------------------------------------------------------------------------
| Does not update another record
|--------------------------------------------------------------------------
*/

it('does not accidentally update another record when id is wrong', function () {
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();
    $clinicService = DoctorService::factory()->create();

    $record = Clinic_doctor_medical_service::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'medicalService_id' => $clinicService->id,
        'price' => 100.00,
        'description' => 'Original',
    ]);

    $wrongId = $record->id + 999;

    $this->service->update([
        'id' => $wrongId,
        'doctor_id' => $doctor->id,
        'medicalService_id' => $clinicService->id,
        'price' => 999.00,
        'description' => 'Wrong target',
    ], $clinic->id);

    $record->refresh();

    expect((float) $record->price)
        ->toBe(100.00);

    expect($record->description)
        ->toBe('Original');
});


/*
|--------------------------------------------------------------------------
| Input data is not modified
|--------------------------------------------------------------------------
*/

it('does not modify the input data', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $data = [
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 275.50,
        'description' => 'Immutable input',
    ];

    $originalData = $data;

    $this->service->update(
        $data,
        $context['clinic']->id
    );

    expect($data)
        ->toBe($originalData);
});


/*
|--------------------------------------------------------------------------
| Price edge cases
|--------------------------------------------------------------------------
*/

it('can update the price to zero', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 0,
        'description' => 'Zero price',
    ], $context['clinic']->id);

    $record = $context['medicalServicePrice']->fresh();

    expect((float) $record->price)
        ->toBe(0.0);
});


it('preserves decimal price accurately', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 1234.75,
        'description' => 'Decimal price',
    ], $context['clinic']->id);

    $price = DB::table('doctor_service_prices')
        ->where('id', $context['medicalServicePrice']->id)
        ->value('price');

    expect((float) $price)
        ->toBe(1234.75);
});


/*
|--------------------------------------------------------------------------
| Description edge cases
|--------------------------------------------------------------------------
*/

it('can update description to an empty string', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => '',
    ], $context['clinic']->id);

    $record = $context['medicalServicePrice']->fresh();

    expect($record->description)
        ->toBe('');
});


/*
|--------------------------------------------------------------------------
| Null description
|--------------------------------------------------------------------------
|
| Your current database schema has description NOT NULL.
| Therefore the correct test is rejection, not successful update.
|--------------------------------------------------------------------------
*/

it('rejects a null description because the database column is not nullable', function () {
    $context = makeUpdateDoctorServicePriceContext();

    expect(fn () => $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => null,
    ], $context['clinic']->id))
        ->toThrow(QueryException::class);

    $context['medicalServicePrice']->refresh();

    expect($context['medicalServicePrice']->description)
        ->toBe('Original description');
});


/*
|--------------------------------------------------------------------------
| Existing record remains unchanged when update fails
|--------------------------------------------------------------------------
*/

it('keeps the original record unchanged when database update fails', function () {
    $context = makeUpdateDoctorServicePriceContext();

    try {
        $this->service->update([
            'id' => $context['medicalServicePrice']->id,
            'doctor_id' => $context['doctor']->id,
            'medicalService_id' => $context['clinicService']->id,
            'price' => 999.00,
            'description' => null,
        ], $context['clinic']->id);
    } catch (QueryException) {
        // Expected.
    }

    $record = $context['medicalServicePrice']->fresh();

    expect((float) $record->price)
        ->toBe(150.00);

    expect($record->description)
        ->toBe('Original description');

    expect($record->doctor_id)
        ->toBe($context['doctor']->id);

    expect($record->medicalService_id)
        ->toBe($context['clinicService']->id);

    expect($record->clinic_id)
        ->toBe($context['clinic']->id);
});


/*
|--------------------------------------------------------------------------
| Different clinics
|--------------------------------------------------------------------------
*/

it('can move the record to another clinic', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $anotherClinic = Clinic::factory()->create();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 400.00,
        'description' => 'Moved clinic',
    ], $anotherClinic->id);

    $record = $context['medicalServicePrice']->fresh();

    expect($record->clinic_id)
        ->toBe($anotherClinic->id);
});


/*
|--------------------------------------------------------------------------
| Relationship integrity
|--------------------------------------------------------------------------
*/

it('maintains valid relationships after update', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $anotherDoctor = Doctor::factory()->create();
    $anotherDoctorService = DoctorService::factory()->create();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $anotherDoctor->id,
        'medicalService_id' => $anotherDoctorService->id,
        'price' => 500.00,
        'description' => 'Valid relationships',
    ], $context['clinic']->id);

    $record = $context['medicalServicePrice']
        ->fresh()
        ->load([
            'clinic',
            'doctor',
            'medicalService',
        ]);

    expect($record->clinic->id)
        ->toBe($context['clinic']->id);

    expect($record->doctor->id)
        ->toBe($anotherDoctor->id);

    expect($record->medicalService->id)
        ->toBe($anotherDoctorService->id);
});


/*
|--------------------------------------------------------------------------
| Existing records are not deleted
|--------------------------------------------------------------------------
*/

it('does not delete any records', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $beforeCount = Clinic_doctor_medical_service::count();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 600.00,
        'description' => 'Updated without deletion',
    ], $context['clinic']->id);

    expect(Clinic_doctor_medical_service::count())
        ->toBe($beforeCount);

    expect(
        Clinic_doctor_medical_service::whereKey(
            $context['medicalServicePrice']->id
        )->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Multiple updates
|--------------------------------------------------------------------------
*/

it('can update the same record multiple times', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => 'First update',
    ], $context['clinic']->id);

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 300.00,
        'description' => 'Second update',
    ], $context['clinic']->id);

    $record = $context['medicalServicePrice']->fresh();

    expect((float) $record->price)
        ->toBe(300.00);

    expect($record->description)
        ->toBe('Second update');
});


/*
|--------------------------------------------------------------------------
| Unique constraint
|--------------------------------------------------------------------------
*/

it('rejects an update that violates the unique clinic doctor service combination', function () {
    $clinic = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create();
    $doctorTwo = Doctor::factory()->create();

    $clinicServiceOne = DoctorService::factory()->create();
    $clinicServiceTwo = DoctorService::factory()->create();

    $first = Clinic_doctor_medical_service::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorOne->id,
        'medicalService_id' => $clinicServiceOne->id,
        'price' => 100.00,
        'description' => 'First',
    ]);

    Clinic_doctor_medical_service::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorTwo->id,
        'medicalService_id' => $clinicServiceTwo->id,
        'price' => 200.00,
        'description' => 'Second',
    ]);

    expect(fn () => $this->service->update([
        'id' => $first->id,
        'doctor_id' => $doctorTwo->id,
        'medicalService_id' => $clinicServiceTwo->id,
        'price' => 999.00,
        'description' => 'Duplicate combination',
    ], $clinic->id))
        ->toThrow(QueryException::class);
});


/*
|--------------------------------------------------------------------------
| Unique constraint failure preserves original record
|--------------------------------------------------------------------------
*/

it('preserves the original record when a unique constraint is violated', function () {
    $clinic = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create();
    $doctorTwo = Doctor::factory()->create();

    $serviceOne = DoctorService::factory()->create();
    $serviceTwo = DoctorService::factory()->create();

    $first = Clinic_doctor_medical_service::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorOne->id,
        'medicalService_id' => $serviceOne->id,
        'price' => 100.00,
        'description' => 'First',
    ]);

    Clinic_doctor_medical_service::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorTwo->id,
        'medicalService_id' => $serviceTwo->id,
        'price' => 200.00,
        'description' => 'Second',
    ]);

    try {
        $this->service->update([
            'id' => $first->id,
            'doctor_id' => $doctorTwo->id,
            'medicalService_id' => $serviceTwo->id,
            'price' => 999.00,
            'description' => 'Should fail',
        ], $clinic->id);
    } catch (QueryException) {
        // Expected.
    }

    $first->refresh();

    expect($first->doctor_id)
        ->toBe($doctorOne->id);

    expect($first->medicalService_id)
        ->toBe($serviceOne->id);

    expect((float) $first->price)
        ->toBe(100.00);

    expect($first->description)
        ->toBe('First');
});


/*
|--------------------------------------------------------------------------
| Database row count remains unchanged
|--------------------------------------------------------------------------
*/

it('does not change the number of database rows', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $before = DB::table('doctor_service_prices')->count();

    $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 700.00,
        'description' => 'Updated',
    ], $context['clinic']->id);

    $after = DB::table('doctor_service_prices')->count();

    expect($after)
        ->toBe($before);
});


/*
|--------------------------------------------------------------------------
| Returned result for successful update
|--------------------------------------------------------------------------
*/

it('returns one when one existing row is actually changed', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $result = $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 999.00,
        'description' => 'Changed',
    ], $context['clinic']->id);

    expect($result)
        ->toBe(true);
});


/*
|--------------------------------------------------------------------------
| Same values behavior
|--------------------------------------------------------------------------
*/

it('can return zero when updating with values identical to the existing values', function () {
    $context = makeUpdateDoctorServicePriceContext();

    $result = $this->service->update([
        'id' => $context['medicalServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'medicalService_id' => $context['clinicService']->id,
        'price' => 150.00,
        'description' => 'Original description',
    ], $context['clinic']->id);

   expect($result)->toBeIn([true, false]);

    $record = $context['medicalServicePrice']->fresh();

    expect($record->clinic_id)
        ->toBe($context['clinic']->id);

    expect($record->doctor_id)
        ->toBe($context['doctor']->id);

    expect($record->medicalService_id)
        ->toBe($context['clinicService']->id);

    expect((float) $record->price)
        ->toBe(150.00);

    expect($record->description)
        ->toBe('Original description');
});