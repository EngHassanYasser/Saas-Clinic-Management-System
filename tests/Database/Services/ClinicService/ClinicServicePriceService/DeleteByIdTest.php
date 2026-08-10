<?php

use App\Models\Clinic;
use App\Models\DoctorService;
use App\Models\Doctor;
use App\Models\Clinic_doctor_medicalService;
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

function makeDeleteDoctorServicePriceContext(): array
{
    $clinic = Clinic::factory()->create();

    $doctor = Doctor::factory()->create();

    $clinicService = DoctorService::factory()->create();

    $medicalServicePrice = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinic->id,
        'doctor_id' => $doctor->id,
        'medicalService_id' => $clinicService->id,
        'price' => 250.50,
        'description' => 'Test service price',
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

it('returns a boolean', function () {
    $context = makeDeleteDoctorServicePriceContext();

    $result = $this->service->deleteById(
        $context['medicalServicePrice']->id
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
    $context = makeDeleteDoctorServicePriceContext();

    $result = $this->service->deleteById(
        $context['medicalServicePrice']->id
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
    $context = makeDeleteDoctorServicePriceContext();

    $id = $context['medicalServicePrice']->id;

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
    $context = makeDeleteDoctorServicePriceContext();

    $id = $context['medicalServicePrice']->id;

    $this->service->deleteById($id);

    expect(
        Clinic_doctor_medicalService::whereKey($id)->exists()
    )->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| find returns null after deletion
|--------------------------------------------------------------------------
*/

it('cannot find the deleted record anymore', function () {
    $context = makeDeleteDoctorServicePriceContext();

    $id = $context['medicalServicePrice']->id;

    $this->service->deleteById($id);

    expect(
        Clinic_doctor_medicalService::find($id)
    )->toBeNull();
});


/*
|--------------------------------------------------------------------------
| Database count
|--------------------------------------------------------------------------
*/

it('decreases the database record count by exactly one', function () {
    $context = makeDeleteDoctorServicePriceContext();

    $before = Clinic_doctor_medicalService::count();

    $this->service->deleteById(
        $context['medicalServicePrice']->id
    );

    $after = Clinic_doctor_medicalService::count();

    expect($after)
        ->toBe($before - 1);
});


/*
|--------------------------------------------------------------------------
| Deletes only requested record
|--------------------------------------------------------------------------
*/

it('deletes only the requested record', function () {
    $first = makeDeleteDoctorServicePriceContext();
    $second = makeDeleteDoctorServicePriceContext();
    $third = makeDeleteDoctorServicePriceContext();

    $firstId = $first['medicalServicePrice']->id;
    $secondId = $second['medicalServicePrice']->id;
    $thirdId = $third['medicalServicePrice']->id;

    $this->service->deleteById($secondId);

    expect(
        Clinic_doctor_medicalService::whereKey($firstId)->exists()
    )->toBeTrue();

    expect(
        Clinic_doctor_medicalService::whereKey($secondId)->exists()
    )->toBeFalse();

    expect(
        Clinic_doctor_medicalService::whereKey($thirdId)->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Other records remain unchanged
|--------------------------------------------------------------------------
*/

it('keeps all other records unchanged', function () {
    $first = makeDeleteDoctorServicePriceContext();
    $second = makeDeleteDoctorServicePriceContext();
    $third = makeDeleteDoctorServicePriceContext();

    $secondId = $second['medicalServicePrice']->id;

    $firstBefore = $first['medicalServicePrice']->fresh()->toArray();
    $thirdBefore = $third['medicalServicePrice']->fresh()->toArray();

    $this->service->deleteById($secondId);

    $firstAfter = Clinic_doctor_medicalService::findOrFail(
        $first['medicalServicePrice']->id
    )->toArray();

    $thirdAfter = Clinic_doctor_medicalService::findOrFail(
        $third['medicalServicePrice']->id
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
        Clinic_doctor_medicalService::whereKey($nonExistingId)->exists()
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
    makeDeleteDoctorServicePriceContext();
    makeDeleteDoctorServicePriceContext();
    makeDeleteDoctorServicePriceContext();

    $before = Clinic_doctor_medicalService::count();

    $result = $this->service->deleteById(999999);

    $after = Clinic_doctor_medicalService::count();

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
    $context = makeDeleteDoctorServicePriceContext();

    $id = $context['medicalServicePrice']->id;

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
    $context = makeDeleteDoctorServicePriceContext();

    $id = $context['medicalServicePrice']->id;

    $this->service->deleteById($id);

    $countAfterFirstDelete = Clinic_doctor_medicalService::count();

    $this->service->deleteById($id);

    $countAfterSecondDelete = Clinic_doctor_medicalService::count();

    expect($countAfterSecondDelete)
        ->toBe($countAfterFirstDelete);
});


/*
|--------------------------------------------------------------------------
| Correct record ID
|--------------------------------------------------------------------------
*/

it('deletes the exact record identified by the given id', function () {
    $first = makeDeleteDoctorServicePriceContext();
    $second = makeDeleteDoctorServicePriceContext();

    $targetId = $second['medicalServicePrice']->id;

    $this->service->deleteById($targetId);

    expect(
        Clinic_doctor_medicalService::whereKey($targetId)->exists()
    )->toBeFalse();

    expect(
        Clinic_doctor_medicalService::whereKey(
            $first['medicalServicePrice']->id
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

    $this->service->deleteById($first->id);

    expect(
        Clinic_doctor_medicalService::whereKey($first->id)->exists()
    )->toBeFalse();

    expect(
        Clinic_doctor_medicalService::whereKey($second->id)->exists()
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

    $this->service->deleteById($first->id);

    expect(
        Clinic_doctor_medicalService::whereKey($first->id)->exists()
    )->toBeFalse();

    expect(
        Clinic_doctor_medicalService::whereKey($second->id)->exists()
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

    $clinicServiceOne = DoctorService::factory()->create();
    $clinicServiceTwo = DoctorService::factory()->create();

    $first = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinicOne->id,
        'doctor_id' => $doctor->id,
        'medicalService_id' => $clinicServiceOne->id,
    ]);

    $second = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinicTwo->id,
        'doctor_id' => $doctor->id,
        'medicalService_id' => $clinicServiceTwo->id,
    ]);

    $this->service->deleteById($first->id);

    expect(
        Clinic_doctor_medicalService::whereKey($first->id)->exists()
    )->toBeFalse();

    expect(
        Clinic_doctor_medicalService::whereKey($second->id)->exists()
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

    $clinicService = DoctorService::factory()->create();

    $first = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinicOne->id,
        'doctor_id' => $doctorOne->id,
        'medicalService_id' => $clinicService->id,
    ]);

    $second = Clinic_doctor_medicalService::factory()->create([
        'clinic_id' => $clinicTwo->id,
        'doctor_id' => $doctorTwo->id,
        'medicalService_id' => $clinicService->id,
    ]);

    $this->service->deleteById($first->id);

    expect(
        Clinic_doctor_medicalService::whereKey($first->id)->exists()
    )->toBeFalse();

    expect(
        Clinic_doctor_medicalService::whereKey($second->id)->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Persisted model state before deletion
|--------------------------------------------------------------------------
*/

it('deletes a persisted record regardless of its field values', function () {
    $context = makeDeleteDoctorServicePriceContext();

    $record = $context['medicalServicePrice'];

    expect($record->exists)
        ->toBeTrue();

    expect(
        Clinic_doctor_medicalService::whereKey($record->id)->exists()
    )->toBeTrue();

    $result = $this->service->deleteById($record->id);

    expect($result)
        ->toBeTrue();

    expect(
        Clinic_doctor_medicalService::whereKey($record->id)->exists()
    )->toBeFalse();
});


/*
|--------------------------------------------------------------------------
| Database row verification
|--------------------------------------------------------------------------
*/

it('removes exactly the targeted database row', function () {
    $first = makeDeleteDoctorServicePriceContext();
    $second = makeDeleteDoctorServicePriceContext();
    $third = makeDeleteDoctorServicePriceContext();

    $targetId = $second['medicalServicePrice']->id;

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
        ->toContain($first['medicalServicePrice']->id)
        ->toContain($third['medicalServicePrice']->id);

    expect(count($afterIds))
        ->toBe(count($beforeIds) - 1);
});


/*
|--------------------------------------------------------------------------
| Multiple records deletion
|--------------------------------------------------------------------------
*/

it('can delete multiple records independently', function () {
    $first = makeDeleteDoctorServicePriceContext();
    $second = makeDeleteDoctorServicePriceContext();
    $third = makeDeleteDoctorServicePriceContext();

    $firstResult = $this->service->deleteById(
        $first['medicalServicePrice']->id
    );

    $secondResult = $this->service->deleteById(
        $second['medicalServicePrice']->id
    );

    $thirdResult = $this->service->deleteById(
        $third['medicalServicePrice']->id
    );

    expect($firstResult)
        ->toBeTrue();

    expect($secondResult)
        ->toBeTrue();

    expect($thirdResult)
        ->toBeTrue();

    expect(Clinic_doctor_medicalService::count())
        ->toBe(0);
});


/*
|--------------------------------------------------------------------------
| Large number of records
|--------------------------------------------------------------------------
*/

it('deletes one record correctly when many records exist', function () {
    $records = Clinic_doctor_medicalService::factory()
        ->count(10)
        ->create();

    $target = $records->get(5);

    $before = Clinic_doctor_medicalService::count();

    $result = $this->service->deleteById($target->id);

    $after = Clinic_doctor_medicalService::count();

    expect($result)
        ->toBeTrue();

    expect($after)
        ->toBe($before - 1);

    expect(
        Clinic_doctor_medicalService::whereKey($target->id)->exists()
    )->toBeFalse();

    foreach ($records as $record) {
        if ($record->id === $target->id) {
            continue;
        }

        expect(
            Clinic_doctor_medicalService::whereKey($record->id)->exists()
        )->toBeTrue();
    }
});


/*
|--------------------------------------------------------------------------
| Foreign key records remain
|--------------------------------------------------------------------------
*/

it('does not delete the related clinic', function () {
    $context = makeDeleteDoctorServicePriceContext();

    $clinicId = $context['clinic']->id;

    $this->service->deleteById(
        $context['medicalServicePrice']->id
    );

    expect(
        Clinic::whereKey($clinicId)->exists()
    )->toBeTrue();
});


it('does not delete the related doctor', function () {
    $context = makeDeleteDoctorServicePriceContext();

    $doctorId = $context['doctor']->id;

    $this->service->deleteById(
        $context['medicalServicePrice']->id
    );

    expect(
        Doctor::whereKey($doctorId)->exists()
    )->toBeTrue();
});


it('does not delete the related clinic service', function () {
    $context = makeDeleteDoctorServicePriceContext();

    $clinicServiceId = $context['clinicService']->id;

    $this->service->deleteById(
        $context['medicalServicePrice']->id
    );

    expect(
        DoctorService::whereKey($clinicServiceId)->exists()
    )->toBeTrue();
});


/*
|--------------------------------------------------------------------------
| Relationships remain usable
|--------------------------------------------------------------------------
*/

it('keeps related models available after deleting the price record', function () {
    $context = makeDeleteDoctorServicePriceContext();

    $clinicId = $context['clinic']->id;
    $doctorId = $context['doctor']->id;
    $clinicServiceId = $context['clinicService']->id;

    $this->service->deleteById(
        $context['medicalServicePrice']->id
    );

    expect(Clinic::find($clinicId))
        ->not->toBeNull();

    expect(Doctor::find($doctorId))
        ->not->toBeNull();

    expect(DoctorService::find($clinicServiceId))
        ->not->toBeNull();
});


/*
|--------------------------------------------------------------------------
| Deleting last record
|--------------------------------------------------------------------------
*/

it('can delete the last remaining doctor service price', function () {
    $context = makeDeleteDoctorServicePriceContext();

    expect(Clinic_doctor_medicalService::count())
        ->toBe(1);

    $result = $this->service->deleteById(
        $context['medicalServicePrice']->id
    );

    expect($result)
        ->toBeTrue();

    expect(Clinic_doctor_medicalService::count())
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

    expect(Clinic_doctor_medicalService::count())
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

    expect(Clinic_doctor_medicalService::count())
        ->toBe(0);
});


/*
|--------------------------------------------------------------------------
| Existing IDs remain unique after deletion
|--------------------------------------------------------------------------
*/

it('does not affect the ids of remaining records', function () {
    $first = makeDeleteDoctorServicePriceContext();
    $second = makeDeleteDoctorServicePriceContext();
    $third = makeDeleteDoctorServicePriceContext();

    $firstId = $first['medicalServicePrice']->id;
    $secondId = $second['medicalServicePrice']->id;
    $thirdId = $third['medicalServicePrice']->id;

    $this->service->deleteById($secondId);

    $remainingIds = Clinic_doctor_medicalService::pluck('id')
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
    makeDeleteDoctorServicePriceContext();
    makeDeleteDoctorServicePriceContext();
    makeDeleteDoctorServicePriceContext();

    $target = Clinic_doctor_medicalService::first();

    $before = Clinic_doctor_medicalService::count();

    $this->service->deleteById($target->id);

    $after = Clinic_doctor_medicalService::count();

    expect($before - $after)
        ->toBe(1);
});


/*
|--------------------------------------------------------------------------
| Delete based only on primary key
|--------------------------------------------------------------------------
*/

it('does not require clinic id, doctor id or service id to delete', function () {
    $context = makeDeleteDoctorServicePriceContext();

    $id = $context['medicalServicePrice']->id;

    $result = $this->service->deleteById($id);

    expect($result)
        ->toBeTrue();

    expect(
        Clinic_doctor_medicalService::whereKey($id)->exists()
    )->toBeFalse();
});
