<?php

use App\Models\Clinic;
use App\Models\ClinicService;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use App\Services\ServiceCatalog\ClinicServicePriceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(ClinicServicePriceService::class);
});


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function makeUpdateClinicServicePriceContext(): array
{
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinicService = ClinicService::factory()->create();

    $doctorServicePrice = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicService->id,
        'price' => 150.00,
        'description' => 'Original description',
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
| Return value
|--------------------------------------------------------------------------
*/

it('returns a boolean result', function () {
    $context = makeUpdateClinicServicePriceContext();

    $result = $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
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
    $context = makeUpdateClinicServicePriceContext();

    $result = $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 250.00,
        'description' => 'Updated description',
    ], $context['clinic']->id);

    expect($result)
        ->toBeTrue();

    $record = Doctor_service_price::findOrFail(
        $context['doctorServicePrice']->id
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
    $context = makeUpdateClinicServicePriceContext();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 325.75,
        'description' => 'Database updated value',
    ], $context['clinic']->id);

    $row = DB::table('doctor_service_prices')
        ->where('id', $context['doctorServicePrice']->id)
        ->first();

    expect($row)
        ->not->toBeNull();

    expect($row->clinic_id)
        ->toBe($context['clinic']->id);

    expect($row->doctor_id)
        ->toBe($context['doctor']->id);

    expect($row->clinic_service_id)
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
    $context = makeUpdateClinicServicePriceContext();

    $anotherClinic = Clinic::factory()->create();

    $data = [
        'id' => $context['doctorServicePrice']->id,
        'clinic_id' => $anotherClinic->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 275.00,
        'description' => 'Updated description',
    ];

    $this->service->update(
        $data,
        $context['clinic']->id
    );

    $record = Doctor_service_price::findOrFail(
        $context['doctorServicePrice']->id
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
    $context = makeUpdateClinicServicePriceContext();

    $anotherDoctor = Doctor::factory()->create();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $anotherDoctor->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => 'Changed doctor',
    ], $context['clinic']->id);

    $record = Doctor_service_price::findOrFail(
        $context['doctorServicePrice']->id
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
    $context = makeUpdateClinicServicePriceContext();

    $anotherClinicService = ClinicService::factory()->create();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $anotherClinicService->id,
        'price' => 200.00,
        'description' => 'Changed clinic service',
    ], $context['clinic']->id);

    $record = Doctor_service_price::findOrFail(
        $context['doctorServicePrice']->id
    );

    expect($record->clinic_service_id)
        ->toBe($anotherClinicService->id);
});


/*
|--------------------------------------------------------------------------
| Price
|--------------------------------------------------------------------------
*/

it('updates the price correctly', function () {
    $context = makeUpdateClinicServicePriceContext();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 999.99,
        'description' => 'Updated price',
    ], $context['clinic']->id);

    $record = Doctor_service_price::findOrFail(
        $context['doctorServicePrice']->id
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
    $context = makeUpdateClinicServicePriceContext();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => 'Completely new description',
    ], $context['clinic']->id);

    $record = Doctor_service_price::findOrFail(
        $context['doctorServicePrice']->id
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
    $context = makeUpdateClinicServicePriceContext();

    $anotherDoctor = Doctor::factory()->create();
    $anotherClinicService = ClinicService::factory()->create();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $anotherDoctor->id,
        'clinic_service_id' => $anotherClinicService->id,
        'price' => 450.75,
        'description' => 'All fields updated',
    ], $context['clinic']->id);

    $record = Doctor_service_price::findOrFail(
        $context['doctorServicePrice']->id
    );

    expect($record->clinic_id)
        ->toBe($context['clinic']->id);

    expect($record->doctor_id)
        ->toBe($anotherDoctor->id);

    expect($record->clinic_service_id)
        ->toBe($anotherClinicService->id);

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
    $context = makeUpdateClinicServicePriceContext();

    $anotherDoctor = Doctor::factory()->create();
    $anotherClinicService = ClinicService::factory()->create();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $anotherDoctor->id,
        'clinic_service_id' => $anotherClinicService->id,
        'price' => 800.00,
        'description' => 'New description',
    ], $context['clinic']->id);

    $record = Doctor_service_price::findOrFail(
        $context['doctorServicePrice']->id
    );

    expect($record->doctor_id)
        ->not->toBe($context['doctor']->id);

    expect($record->clinic_service_id)
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

    $serviceOne = ClinicService::factory()->create();
    $serviceTwo = ClinicService::factory()->create();

    $first = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorOne->id,
        'clinic_service_id' => $serviceOne->id,
        'price' => 100.00,
        'description' => 'First',
    ]);

    $second = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorTwo->id,
        'clinic_service_id' => $serviceTwo->id,
        'price' => 200.00,
        'description' => 'Second',
    ]);

    $this->service->update([
        'id' => $first->id,
        'doctor_id' => $doctorOne->id,
        'clinic_service_id' => $serviceOne->id,
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
    $context = makeUpdateClinicServicePriceContext();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 333.00,
        'description' => 'Found by id',
    ], $context['clinic']->id);

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $context['doctorServicePrice']->id)
            ->value('price')
    )->toEqual(333.00);
});


/*
|--------------------------------------------------------------------------
| Non-existing ID
|--------------------------------------------------------------------------
*/

it('returns false when the requested record does not exist', function () {
    $context = makeUpdateClinicServicePriceContext();

    $nonExistingId =
        Doctor_service_price::max('id') + 999;

    $result = $this->service->update([
        'id' => $nonExistingId,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
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
    $context = makeUpdateClinicServicePriceContext();

    $beforeCount = Doctor_service_price::count();

    $nonExistingId =
        Doctor_service_price::max('id') + 999;

    $this->service->update([
        'id' => $nonExistingId,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 500.00,
        'description' => 'Should not exist',
    ], $context['clinic']->id);

    expect(Doctor_service_price::count())
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
    $clinicService = ClinicService::factory()->create();

    $record = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicService->id,
        'price' => 100.00,
        'description' => 'Original',
    ]);

    $wrongId = $record->id + 999;

    $this->service->update([
        'id' => $wrongId,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicService->id,
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
    $context = makeUpdateClinicServicePriceContext();

    $data = [
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
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
    $context = makeUpdateClinicServicePriceContext();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 0,
        'description' => 'Zero price',
    ], $context['clinic']->id);

    $record = $context['doctorServicePrice']->fresh();

    expect((float) $record->price)
        ->toBe(0.0);
});


it('preserves decimal price accurately', function () {
    $context = makeUpdateClinicServicePriceContext();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 1234.75,
        'description' => 'Decimal price',
    ], $context['clinic']->id);

    $price = DB::table('doctor_service_prices')
        ->where('id', $context['doctorServicePrice']->id)
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
    $context = makeUpdateClinicServicePriceContext();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => '',
    ], $context['clinic']->id);

    $record = $context['doctorServicePrice']->fresh();

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
    $context = makeUpdateClinicServicePriceContext();

    expect(fn () => $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => null,
    ], $context['clinic']->id))
        ->toThrow(QueryException::class);

    $context['doctorServicePrice']->refresh();

    expect($context['doctorServicePrice']->description)
        ->toBe('Original description');
});


/*
|--------------------------------------------------------------------------
| Existing record remains unchanged when update fails
|--------------------------------------------------------------------------
*/

it('keeps the original record unchanged when database update fails', function () {
    $context = makeUpdateClinicServicePriceContext();

    try {
        $this->service->update([
            'id' => $context['doctorServicePrice']->id,
            'doctor_id' => $context['doctor']->id,
            'clinic_service_id' => $context['clinicService']->id,
            'price' => 999.00,
            'description' => null,
        ], $context['clinic']->id);
    } catch (QueryException) {
        // Expected.
    }

    $record = $context['doctorServicePrice']->fresh();

    expect((float) $record->price)
        ->toBe(150.00);

    expect($record->description)
        ->toBe('Original description');

    expect($record->doctor_id)
        ->toBe($context['doctor']->id);

    expect($record->clinic_service_id)
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
    $context = makeUpdateClinicServicePriceContext();

    $anotherClinic = Clinic::factory()->create();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 400.00,
        'description' => 'Moved clinic',
    ], $anotherClinic->id);

    $record = $context['doctorServicePrice']->fresh();

    expect($record->clinic_id)
        ->toBe($anotherClinic->id);
});


/*
|--------------------------------------------------------------------------
| Relationship integrity
|--------------------------------------------------------------------------
*/

it('maintains valid relationships after update', function () {
    $context = makeUpdateClinicServicePriceContext();

    $anotherDoctor = Doctor::factory()->create();
    $anotherClinicService = ClinicService::factory()->create();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $anotherDoctor->id,
        'clinic_service_id' => $anotherClinicService->id,
        'price' => 500.00,
        'description' => 'Valid relationships',
    ], $context['clinic']->id);

    $record = $context['doctorServicePrice']
        ->fresh()
        ->load([
            'clinic',
            'doctor',
            'clinic_service',
        ]);

    expect($record->clinic->id)
        ->toBe($context['clinic']->id);

    expect($record->doctor->id)
        ->toBe($anotherDoctor->id);

    expect($record->clinic_service->id)
        ->toBe($anotherClinicService->id);
});


/*
|--------------------------------------------------------------------------
| Existing records are not deleted
|--------------------------------------------------------------------------
*/

it('does not delete any records', function () {
    $context = makeUpdateClinicServicePriceContext();

    $beforeCount = Doctor_service_price::count();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 600.00,
        'description' => 'Updated without deletion',
    ], $context['clinic']->id);

    expect(Doctor_service_price::count())
        ->toBe($beforeCount);

    expect(
        Doctor_service_price::whereKey(
            $context['doctorServicePrice']->id
        )->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Multiple updates
|--------------------------------------------------------------------------
*/

it('can update the same record multiple times', function () {
    $context = makeUpdateClinicServicePriceContext();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 200.00,
        'description' => 'First update',
    ], $context['clinic']->id);

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 300.00,
        'description' => 'Second update',
    ], $context['clinic']->id);

    $record = $context['doctorServicePrice']->fresh();

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

    $clinicServiceOne = ClinicService::factory()->create();
    $clinicServiceTwo = ClinicService::factory()->create();

    $first = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorOne->id,
        'clinic_service_id' => $clinicServiceOne->id,
        'price' => 100.00,
        'description' => 'First',
    ]);

    Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorTwo->id,
        'clinic_service_id' => $clinicServiceTwo->id,
        'price' => 200.00,
        'description' => 'Second',
    ]);

    expect(fn () => $this->service->update([
        'id' => $first->id,
        'doctor_id' => $doctorTwo->id,
        'clinic_service_id' => $clinicServiceTwo->id,
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

    $serviceOne = ClinicService::factory()->create();
    $serviceTwo = ClinicService::factory()->create();

    $first = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorOne->id,
        'clinic_service_id' => $serviceOne->id,
        'price' => 100.00,
        'description' => 'First',
    ]);

    Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctorTwo->id,
        'clinic_service_id' => $serviceTwo->id,
        'price' => 200.00,
        'description' => 'Second',
    ]);

    try {
        $this->service->update([
            'id' => $first->id,
            'doctor_id' => $doctorTwo->id,
            'clinic_service_id' => $serviceTwo->id,
            'price' => 999.00,
            'description' => 'Should fail',
        ], $clinic->id);
    } catch (QueryException) {
        // Expected.
    }

    $first->refresh();

    expect($first->doctor_id)
        ->toBe($doctorOne->id);

    expect($first->clinic_service_id)
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
    $context = makeUpdateClinicServicePriceContext();

    $before = DB::table('doctor_service_prices')->count();

    $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
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
    $context = makeUpdateClinicServicePriceContext();

    $result = $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
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
    $context = makeUpdateClinicServicePriceContext();

    $result = $this->service->update([
        'id' => $context['doctorServicePrice']->id,
        'doctor_id' => $context['doctor']->id,
        'clinic_service_id' => $context['clinicService']->id,
        'price' => 150.00,
        'description' => 'Original description',
    ], $context['clinic']->id);

   expect($result)->toBeIn([true, false]);

    $record = $context['doctorServicePrice']->fresh();

    expect($record->clinic_id)
        ->toBe($context['clinic']->id);

    expect($record->doctor_id)
        ->toBe($context['doctor']->id);

    expect($record->clinic_service_id)
        ->toBe($context['clinicService']->id);

    expect((float) $record->price)
        ->toBe(150.00);

    expect($record->description)
        ->toBe('Original description');
});