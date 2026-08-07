<?php

use App\Models\Clinic;
use App\Models\ClinicService;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use App\Services\ServiceCatalog\ClinicServicePriceService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(ClinicServicePriceService::class);
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function makeDeleteClinicServicePriceContext(): array
{
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinicService = ClinicService::factory()->create();

    $doctorServicePrice = Doctor_service_price::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicService->id,
        'price' => 250.50,
        'description' => 'Test service price',
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

it('returns a boolean', function () {
    $context = makeDeleteClinicServicePriceContext();

    $result = $this->service->deleteById(
        $context['doctorServicePrice']->id
    );

    expect($result)
        ->toBeBool();
});


/*
|--------------------------------------------------------------------------
| Successful deletion
|--------------------------------------------------------------------------
*/

it('returns true when the record exists and is deleted', function () {
    $context = makeDeleteClinicServicePriceContext();

    $result = $this->service->deleteById(
        $context['doctorServicePrice']->id
    );

    expect($result)
        ->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Record is removed from database
|--------------------------------------------------------------------------
*/

it('deletes the record from the database', function () {
    $context = makeDeleteClinicServicePriceContext();

    $id = $context['doctorServicePrice']->id;

    $this->service->deleteById($id);

    expect(
        DB::table('doctor_service_prices')
            ->where('id', $id)
            ->exists()
    )->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Eloquent existence
|--------------------------------------------------------------------------
*/

it('makes the deleted model no longer exist in the database', function () {
    $context = makeDeleteClinicServicePriceContext();

    $id = $context['doctorServicePrice']->id;

    $this->service->deleteById($id);

    expect(
        Doctor_service_price::whereKey($id)->exists()
    )->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| find returns null after deletion
|--------------------------------------------------------------------------
*/

it('cannot find the deleted record anymore', function () {
    $context = makeDeleteClinicServicePriceContext();

    $id = $context['doctorServicePrice']->id;

    $this->service->deleteById($id);

    expect(
        Doctor_service_price::find($id)
    )->toBeNull();
});


/*
|--------------------------------------------------------------------------
| Database count
|--------------------------------------------------------------------------
*/

it('decreases the database record count by exactly one', function () {
    $context = makeDeleteClinicServicePriceContext();

    $before = Doctor_service_price::count();

    $this->service->deleteById(
        $context['doctorServicePrice']->id
    );

    $after = Doctor_service_price::count();

    expect($after)
        ->toBe($before - 1);
});


/*
|--------------------------------------------------------------------------
| Deletes only requested record
|--------------------------------------------------------------------------
*/

it('deletes only the requested record', function () {
    $first = makeDeleteClinicServicePriceContext();
    $second = makeDeleteClinicServicePriceContext();
    $third = makeDeleteClinicServicePriceContext();

    $firstId = $first['doctorServicePrice']->id;
    $secondId = $second['doctorServicePrice']->id;
    $thirdId = $third['doctorServicePrice']->id;

    $this->service->deleteById($secondId);

    expect(
        Doctor_service_price::whereKey($firstId)->exists()
    )->toBeTrue();

    expect(
        Doctor_service_price::whereKey($secondId)->exists()
    )->toBeFalse();

    expect(
        Doctor_service_price::whereKey($thirdId)->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Other records remain unchanged
|--------------------------------------------------------------------------
*/

it('keeps all other records unchanged', function () {
    $first = makeDeleteClinicServicePriceContext();
    $second = makeDeleteClinicServicePriceContext();
    $third = makeDeleteClinicServicePriceContext();

    $secondId = $second['doctorServicePrice']->id;

    $firstBefore = $first['doctorServicePrice']->fresh()->toArray();
    $thirdBefore = $third['doctorServicePrice']->fresh()->toArray();

    $this->service->deleteById($secondId);

    $firstAfter = Doctor_service_price::findOrFail(
        $first['doctorServicePrice']->id
    )->toArray();

    $thirdAfter = Doctor_service_price::findOrFail(
        $third['doctorServicePrice']->id
    )->toArray();

    expect($firstAfter)
        ->toBe($firstBefore);

    expect($thirdAfter)
        ->toBe($thirdBefore);
});


/*
|--------------------------------------------------------------------------
| Non-existing ID
|--------------------------------------------------------------------------
*/

it('returns false when the record does not exist', function () {
    $nonExistingId = 999999;

    expect(
        Doctor_service_price::whereKey($nonExistingId)->exists()
    )->toBeFalse();

    $result = $this->service->deleteById($nonExistingId);

    expect($result)
        ->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Non-existing ID does not affect database
|--------------------------------------------------------------------------
*/

it('does not change the database when deleting a non-existing id', function () {
    makeDeleteClinicServicePriceContext();
    makeDeleteClinicServicePriceContext();
    makeDeleteClinicServicePriceContext();

    $before = Doctor_service_price::count();

    $result = $this->service->deleteById(999999);

    $after = Doctor_service_price::count();

    expect($result)
        ->toBeFalse();

    expect($after)
        ->toBe($before);
});


/*
|--------------------------------------------------------------------------
| Repeated deletion
|--------------------------------------------------------------------------
*/

it('returns false when deleting the same record twice', function () {
    $context = makeDeleteClinicServicePriceContext();

    $id = $context['doctorServicePrice']->id;

    $firstResult = $this->service->deleteById($id);

    $secondResult = $this->service->deleteById($id);

    expect($firstResult)
        ->toBeTrue();

    expect($secondResult)
        ->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Repeated deletion keeps database unchanged
|--------------------------------------------------------------------------
*/

it('does not change the database on repeated deletion', function () {
    $context = makeDeleteClinicServicePriceContext();

    $id = $context['doctorServicePrice']->id;

    $this->service->deleteById($id);

    $countAfterFirstDelete = Doctor_service_price::count();

    $this->service->deleteById($id);

    $countAfterSecondDelete = Doctor_service_price::count();

    expect($countAfterSecondDelete)
        ->toBe($countAfterFirstDelete);
});


/*
|--------------------------------------------------------------------------
| Correct record ID
|--------------------------------------------------------------------------
*/

it('deletes the exact record identified by the given id', function () {
    $first = makeDeleteClinicServicePriceContext();
    $second = makeDeleteClinicServicePriceContext();

    $targetId = $second['doctorServicePrice']->id;

    $this->service->deleteById($targetId);

    expect(
        Doctor_service_price::whereKey($targetId)->exists()
    )->toBeFalse();

    expect(
        Doctor_service_price::whereKey(
            $first['doctorServicePrice']->id
        )->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Relationships do not determine which record is deleted
|--------------------------------------------------------------------------
*/

it('deletes by primary key regardless of related models', function () {
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

    $this->service->deleteById($first->id);

    expect(
        Doctor_service_price::whereKey($first->id)->exists()
    )->toBeFalse();

    expect(
        Doctor_service_price::whereKey($second->id)->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Same clinic
|--------------------------------------------------------------------------
*/

it('deletes only the selected record when multiple records belong to the same clinic', function () {
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

    $this->service->deleteById($first->id);

    expect(
        Doctor_service_price::whereKey($first->id)->exists()
    )->toBeFalse();

    expect(
        Doctor_service_price::whereKey($second->id)->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Same doctor
|--------------------------------------------------------------------------
*/

it('deletes only the selected record when multiple records belong to the same doctor', function () {
    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinicServiceOne = ClinicService::factory()->create();
    $clinicServiceTwo = ClinicService::factory()->create();

    $first = Doctor_service_price::factory()->create([
        'clinic_id' => $clinicOne->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicServiceOne->id,
    ]);

    $second = Doctor_service_price::factory()->create([
        'clinic_id' => $clinicTwo->id,
        'doctor_id' => $doctor->id,
        'clinic_service_id' => $clinicServiceTwo->id,
    ]);

    $this->service->deleteById($first->id);

    expect(
        Doctor_service_price::whereKey($first->id)->exists()
    )->toBeFalse();

    expect(
        Doctor_service_price::whereKey($second->id)->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Same clinic service
|--------------------------------------------------------------------------
*/

it('deletes only the selected record when multiple records use the same clinic service', function () {
    $clinicOne = Clinic::factory()->create();
    $clinicTwo = Clinic::factory()->create();

    $doctorOne = Doctor::factory()->create();
    $doctorTwo = Doctor::factory()->create();

    $clinicService = ClinicService::factory()->create();

    $first = Doctor_service_price::factory()->create([
        'clinic_id' => $clinicOne->id,
        'doctor_id' => $doctorOne->id,
        'clinic_service_id' => $clinicService->id,
    ]);

    $second = Doctor_service_price::factory()->create([
        'clinic_id' => $clinicTwo->id,
        'doctor_id' => $doctorTwo->id,
        'clinic_service_id' => $clinicService->id,
    ]);

    $this->service->deleteById($first->id);

    expect(
        Doctor_service_price::whereKey($first->id)->exists()
    )->toBeFalse();

    expect(
        Doctor_service_price::whereKey($second->id)->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Persisted model state before deletion
|--------------------------------------------------------------------------
*/

it('deletes a persisted record regardless of its field values', function () {
    $context = makeDeleteClinicServicePriceContext();

    $record = $context['doctorServicePrice'];

    expect($record->exists)
        ->toBeTrue();

    expect(
        Doctor_service_price::whereKey($record->id)->exists()
    )->toBeTrue();

    $result = $this->service->deleteById($record->id);

    expect($result)
        ->toBeTrue();

    expect(
        Doctor_service_price::whereKey($record->id)->exists()
    )->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Database row verification
|--------------------------------------------------------------------------
*/

it('removes exactly the targeted database row', function () {
    $first = makeDeleteClinicServicePriceContext();
    $second = makeDeleteClinicServicePriceContext();
    $third = makeDeleteClinicServicePriceContext();

    $targetId = $second['doctorServicePrice']->id;

    $beforeIds = DB::table('doctor_service_prices')
        ->pluck('id')
        ->sort()
        ->values()
        ->all();

    $this->service->deleteById($targetId);

    $afterIds = DB::table('doctor_service_prices')
        ->pluck('id')
        ->sort()
        ->values()
        ->all();

    expect($beforeIds)
        ->toContain($targetId);

    expect($afterIds)
        ->not->toContain($targetId);

    expect($afterIds)
        ->toContain($first['doctorServicePrice']->id)
        ->toContain($third['doctorServicePrice']->id);

    expect(count($afterIds))
        ->toBe(count($beforeIds) - 1);
});


/*
|--------------------------------------------------------------------------
| Multiple records deletion
|--------------------------------------------------------------------------
*/

it('can delete multiple records independently', function () {
    $first = makeDeleteClinicServicePriceContext();
    $second = makeDeleteClinicServicePriceContext();
    $third = makeDeleteClinicServicePriceContext();

    $firstResult = $this->service->deleteById(
        $first['doctorServicePrice']->id
    );

    $secondResult = $this->service->deleteById(
        $second['doctorServicePrice']->id
    );

    $thirdResult = $this->service->deleteById(
        $third['doctorServicePrice']->id
    );

    expect($firstResult)
        ->toBeTrue();

    expect($secondResult)
        ->toBeTrue();

    expect($thirdResult)
        ->toBeTrue();

    expect(Doctor_service_price::count())
        ->toBe(0);
});


/*
|--------------------------------------------------------------------------
| Large number of records
|--------------------------------------------------------------------------
*/

it('deletes one record correctly when many records exist', function () {
    $records = Doctor_service_price::factory()
        ->count(10)
        ->create();

    $target = $records->get(5);

    $before = Doctor_service_price::count();

    $result = $this->service->deleteById($target->id);

    $after = Doctor_service_price::count();

    expect($result)
        ->toBeTrue();

    expect($after)
        ->toBe($before - 1);

    expect(
        Doctor_service_price::whereKey($target->id)->exists()
    )->toBeFalse();

    foreach ($records as $record) {
        if ($record->id === $target->id) {
            continue;
        }

        expect(
            Doctor_service_price::whereKey($record->id)->exists()
        )->toBeTrue();
    }
});


/*
|--------------------------------------------------------------------------
| Foreign key records remain
|--------------------------------------------------------------------------
*/

it('does not delete the related clinic', function () {
    $context = makeDeleteClinicServicePriceContext();

    $clinicId = $context['clinic']->id;

    $this->service->deleteById(
        $context['doctorServicePrice']->id
    );

    expect(
        Clinic::whereKey($clinicId)->exists()
    )->toBeTrue();
});


it('does not delete the related doctor', function () {
    $context = makeDeleteClinicServicePriceContext();

    $doctorId = $context['doctor']->id;

    $this->service->deleteById(
        $context['doctorServicePrice']->id
    );

    expect(
        Doctor::whereKey($doctorId)->exists()
    )->toBeTrue();
});


it('does not delete the related clinic service', function () {
    $context = makeDeleteClinicServicePriceContext();

    $clinicServiceId = $context['clinicService']->id;

    $this->service->deleteById(
        $context['doctorServicePrice']->id
    );

    expect(
        ClinicService::whereKey($clinicServiceId)->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Relationships remain usable
|--------------------------------------------------------------------------
*/

it('keeps related models available after deleting the price record', function () {
    $context = makeDeleteClinicServicePriceContext();

    $clinicId = $context['clinic']->id;
    $doctorId = $context['doctor']->id;
    $clinicServiceId = $context['clinicService']->id;

    $this->service->deleteById(
        $context['doctorServicePrice']->id
    );

    expect(Clinic::find($clinicId))
        ->not->toBeNull();

    expect(Doctor::find($doctorId))
        ->not->toBeNull();

    expect(ClinicService::find($clinicServiceId))
        ->not->toBeNull();
});


/*
|--------------------------------------------------------------------------
| Deleting last record
|--------------------------------------------------------------------------
*/

it('can delete the last remaining doctor service price', function () {
    $context = makeDeleteClinicServicePriceContext();

    expect(Doctor_service_price::count())
        ->toBe(1);

    $result = $this->service->deleteById(
        $context['doctorServicePrice']->id
    );

    expect($result)
        ->toBeTrue();

    expect(Doctor_service_price::count())
        ->toBe(0);
});


/*
|--------------------------------------------------------------------------
| ID zero
|--------------------------------------------------------------------------
*/

it('returns false when deleting id zero', function () {
    $result = $this->service->deleteById(0);

    expect($result)
        ->toBeFalse();

    expect(Doctor_service_price::count())
        ->toBe(0);
});


/*
|--------------------------------------------------------------------------
| Negative ID
|--------------------------------------------------------------------------
*/

it('returns false when deleting a negative id', function () {
    $result = $this->service->deleteById(-1);

    expect($result)
        ->toBeFalse();

    expect(Doctor_service_price::count())
        ->toBe(0);
});


/*
|--------------------------------------------------------------------------
| Existing IDs remain unique after deletion
|--------------------------------------------------------------------------
*/

it('does not affect the ids of remaining records', function () {
    $first = makeDeleteClinicServicePriceContext();
    $second = makeDeleteClinicServicePriceContext();
    $third = makeDeleteClinicServicePriceContext();

    $firstId = $first['doctorServicePrice']->id;
    $secondId = $second['doctorServicePrice']->id;
    $thirdId = $third['doctorServicePrice']->id;

    $this->service->deleteById($secondId);

    $remainingIds = Doctor_service_price::pluck('id')
        ->sort()
        ->values()
        ->all();

    expect($remainingIds)
        ->toBe([
            $firstId,
            $thirdId,
        ]);
});


/*
|--------------------------------------------------------------------------
| No accidental mass deletion
|--------------------------------------------------------------------------
*/

it('never deletes more than one record for a single id', function () {
    makeDeleteClinicServicePriceContext();
    makeDeleteClinicServicePriceContext();
    makeDeleteClinicServicePriceContext();

    $target = Doctor_service_price::first();

    $before = Doctor_service_price::count();

    $this->service->deleteById($target->id);

    $after = Doctor_service_price::count();

    expect($before - $after)
        ->toBe(1);
});


/*
|--------------------------------------------------------------------------
| Delete based only on primary key
|--------------------------------------------------------------------------
*/

it('does not require clinic id, doctor id or service id to delete', function () {
    $context = makeDeleteClinicServicePriceContext();

    $id = $context['doctorServicePrice']->id;

    $result = $this->service->deleteById($id);

    expect($result)
        ->toBeTrue();

    expect(
        Doctor_service_price::whereKey($id)->exists()
    )->toBeFalse();
});
