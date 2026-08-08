<?php

use App\Models\Speciality;
use App\Services\Speciality\SpecialityQueryService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(SpecialityQueryService::class);
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function createSpeciality(array $attributes = []): Speciality
{
    return Speciality::factory()->create($attributes);
}

/*
|--------------------------------------------------------------------------
| Return Type
|--------------------------------------------------------------------------
*/

it('returns an Eloquent collection', function () {
    createSpeciality();

    $result = $this->service->getAll();

    expect($result)
        ->toBeInstanceOf(Collection::class);
});

/*
|--------------------------------------------------------------------------
| Empty Database
|--------------------------------------------------------------------------
*/

it('returns an empty collection when there are no specialities', function () {
    $result = $this->service->getAll();

    expect($result)
        ->toBeInstanceOf(Collection::class)
        ->toBeEmpty();
});

/*
|--------------------------------------------------------------------------
| Get All Records
|--------------------------------------------------------------------------
*/

it('returns all specialities from the database', function () {
    $first = createSpeciality();
    $second = createSpeciality();
    $third = createSpeciality();

    $result = $this->service->getAll();

    expect($result)
        ->toHaveCount(3);

    expect($result->pluck('id')->sort()->values()->all())
        ->toBe(
            collect([
                $first->id,
                $second->id,
                $third->id,
            ])->sort()->values()->all()
        );
});

/*
|--------------------------------------------------------------------------
| Correct Model
|--------------------------------------------------------------------------
*/

it('returns Speciality models', function () {
    createSpeciality();

    $result = $this->service->getAll();

    expect(
        $result->every(
            fn (Speciality $speciality) =>
                $speciality instanceof Speciality
        )
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Selected Columns
|--------------------------------------------------------------------------
*/

it('returns only the id and name columns', function () {
    $speciality = createSpeciality([
        'name' => 'Cardiology',
    ]);

    $result = $this->service->getAll();

    $item = $result->firstWhere('id', $speciality->id);

    expect($item)->not->toBeNull();

    expect(array_keys($item->getAttributes()))
        ->toEqualCanonicalizing([
            'id',
            'name',
        ]);
});

/*
|--------------------------------------------------------------------------
| ID
|--------------------------------------------------------------------------
*/

it('returns the correct speciality id', function () {
    $speciality = createSpeciality();

    $result = $this->service->getAll();

    $item = $result->firstWhere('id', $speciality->id);

    expect($item->id)
        ->toBe($speciality->id);
});

/*
|--------------------------------------------------------------------------
| Name
|--------------------------------------------------------------------------
*/

it('returns the correct speciality name', function () {
    $speciality = createSpeciality([
        'name' => 'Cardiology',
    ]);

    $result = $this->service->getAll();

    $item = $result->firstWhere('id', $speciality->id);

    expect($item->name)
        ->toBe('Cardiology');
});

/*
|--------------------------------------------------------------------------
| Correct Data For Every Record
|--------------------------------------------------------------------------
*/

it('returns the correct id and name for every speciality', function () {
    $first = createSpeciality([
        'name' => 'Cardiology',
    ]);

    $second = createSpeciality([
        'name' => 'Dermatology',
    ]);

    $third = createSpeciality([
        'name' => 'Pediatrics',
    ]);

    $result = $this->service->getAll();

    $firstResult = $result->firstWhere('id', $first->id);
    $secondResult = $result->firstWhere('id', $second->id);
    $thirdResult = $result->firstWhere('id', $third->id);

    expect($firstResult->id)
        ->toBe($first->id);

    expect($firstResult->name)
        ->toBe('Cardiology');

    expect($secondResult->id)
        ->toBe($second->id);

    expect($secondResult->name)
        ->toBe('Dermatology');

    expect($thirdResult->id)
        ->toBe($third->id);

    expect($thirdResult->name)
        ->toBe('Pediatrics');
});

/*
|--------------------------------------------------------------------------
| Does Not Return Non-Existing Records
|--------------------------------------------------------------------------
*/

it('does not return specialities that do not exist in the database', function () {
    $existing = createSpeciality();

    $nonExistingId = $existing->id + 999999;

    $result = $this->service->getAll();

    expect($result->pluck('id'))
        ->not->toContain($nonExistingId);
});

/*
|--------------------------------------------------------------------------
| No Duplicate Records
|--------------------------------------------------------------------------
*/

it('returns every database record exactly once', function () {
    $first = createSpeciality();
    $second = createSpeciality();
    $third = createSpeciality();

    $result = $this->service->getAll();

    $ids = $result->pluck('id');

    expect($ids)
        ->toHaveCount(3);

    expect($ids->unique()->count())
        ->toBe(3);

    expect($ids)
        ->toContain($first->id)
        ->toContain($second->id)
        ->toContain($third->id);
});

/*
|--------------------------------------------------------------------------
| Similar Names
|--------------------------------------------------------------------------
*/

it('correctly handles specialities with similar names', function () {
    $first = createSpeciality([
        'name' => 'Internal Medicine',
    ]);

    $second = createSpeciality([
        'name' => 'Internal Medicine Advanced',
    ]);

    $third = createSpeciality([
        'name' => 'Internal Medicine Pediatric',
    ]);

    $result = $this->service->getAll();

    expect($result->firstWhere('id', $first->id)->name)
        ->toBe('Internal Medicine');

    expect($result->firstWhere('id', $second->id)->name)
        ->toBe('Internal Medicine Advanced');

    expect($result->firstWhere('id', $third->id)->name)
        ->toBe('Internal Medicine Pediatric');
});

/*
|--------------------------------------------------------------------------
| Empty String
|--------------------------------------------------------------------------
*/

it('returns the minimum valid speciality name correctly', function () {
    $speciality = createSpeciality([
        'name' => 'A',
    ]);

    $result = $this->service->getAll();

    $item = $result->firstWhere('id', $speciality->id);

    expect($item)->not->toBeNull()
        ->and($item->name)->toBe('A');
});
/*
|--------------------------------------------------------------------------
| Special Characters
|--------------------------------------------------------------------------
*/

it('returns speciality names containing special characters correctly', function () {
    $name = 'ENT & Head-Neck';

    $speciality = createSpeciality([
        'name' => $name,
    ]);

    $result = $this->service->getAll();

    $item = $result->firstWhere('id', $speciality->id);

    expect($item->name)
        ->toBe($name);
});

/*
|--------------------------------------------------------------------------
| Unicode
|--------------------------------------------------------------------------
*/

it('returns unicode speciality names correctly', function () {
    $name = 'طب الأطفال';

    $speciality = createSpeciality([
        'name' => $name,
    ]);

    $result = $this->service->getAll();

    $item = $result->firstWhere('id', $speciality->id);

    expect($item->name)
        ->toBe($name);
});

/*
|--------------------------------------------------------------------------
| Long Valid Name
|--------------------------------------------------------------------------
*/

it('returns a long valid speciality name correctly', function () {
    $name = str_repeat('Medical ', 10);

    $speciality = createSpeciality([
        'name' => $name,
    ]);

    $result = $this->service->getAll();

    $item = $result->firstWhere('id', $speciality->id);

    expect($item->name)
        ->toBe($name);
});

/*
|--------------------------------------------------------------------------
| Database Persistence
|--------------------------------------------------------------------------
*/

it('returns records that actually exist in the database', function () {
    $speciality = createSpeciality();

    $result = $this->service->getAll();

    expect(
        $result->contains(
            fn (Speciality $item) =>
                $item->id === $speciality->id
        )
    )->toBeTrue();

    expect(
        DB::table('specialities')
            ->where('id', $speciality->id)
            ->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Database Count Is Not Changed
|--------------------------------------------------------------------------
*/

it('does not modify the number of database records', function () {
    createSpeciality();
    createSpeciality();
    createSpeciality();

    $before = Speciality::count();

    $this->service->getAll();

    $after = Speciality::count();

    expect($after)
        ->toBe($before);
});

/*
|--------------------------------------------------------------------------
| Does Not Mutate Existing Records
|--------------------------------------------------------------------------
*/

it('does not modify existing speciality records', function () {
    $first = createSpeciality([
        'name' => 'Cardiology',
    ]);

    $second = createSpeciality([
        'name' => 'Dermatology',
    ]);

    $this->service->getAll();

    $first->refresh();
    $second->refresh();

    expect($first->name)
        ->toBe('Cardiology');

    expect($second->name)
        ->toBe('Dermatology');
});

/*
|--------------------------------------------------------------------------
| Query Count
|--------------------------------------------------------------------------
*/

it('executes exactly one database query', function () {
    createSpeciality();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->service->getAll();

    $queries = DB::getQueryLog();

    DB::disableQueryLog();

    expect(count($queries))
        ->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Query Count Constant
|--------------------------------------------------------------------------
*/

it('keeps query count constant regardless of number of records', function () {
    Cache::forget( 'speciality.all');

    createSpeciality();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $this->service->getAll();
    Cache::forget( 'speciality.all');

    $queriesForOneRecord = count(DB::getQueryLog());

    DB::disableQueryLog();

    createSpeciality();
    createSpeciality();
    createSpeciality();
    createSpeciality();

    DB::flushQueryLog();
    DB::enableQueryLog();
    Cache::forget( 'speciality.all');

    $this->service->getAll();

    $queriesForFiveRecords = count(DB::getQueryLog());

    DB::disableQueryLog();

    expect($queriesForOneRecord)
        ->toBe(1);

    expect($queriesForFiveRecords)
        ->toBe(1);
});

/*
|--------------------------------------------------------------------------
| No N+1 Queries
|--------------------------------------------------------------------------
*/

it('does not execute additional queries when accessing returned attributes', function () {
    createSpeciality();
    createSpeciality();
    createSpeciality();
    createSpeciality();
    createSpeciality();

    DB::flushQueryLog();
    DB::enableQueryLog();

    $result = $this->service->getAll();

    foreach ($result as $speciality) {
        $speciality->id;
        $speciality->name;
    }

    $queries = DB::getQueryLog();

    DB::disableQueryLog();

    expect(count($queries))
        ->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Multiple Calls
|--------------------------------------------------------------------------
*/

it('returns consistent results when called multiple times', function () {
    createSpeciality([
        'name' => 'Cardiology',
    ]);

    createSpeciality([
        'name' => 'Dermatology',
    ]);

    $firstResult = $this->service->getAll();
    $secondResult = $this->service->getAll();

    expect($firstResult->count())
        ->toBe($secondResult->count());

    expect(
        $firstResult->pluck('id')->sort()->values()->all()
    )->toBe(
        $secondResult->pluck('id')->sort()->values()->all()
    );

    expect(
        $firstResult->pluck('name')->sort()->values()->all()
    )->toBe(
        $secondResult->pluck('name')->sort()->values()->all()
    );
});

/*
|--------------------------------------------------------------------------
| New Records Become Visible
|--------------------------------------------------------------------------
*/



/*
|--------------------------------------------------------------------------
| Does Not Return Deleted Records
|--------------------------------------------------------------------------
*/

it('does not return deleted records', function () {
    $first = createSpeciality([
        'name' => 'Cardiology',
    ]);

    $second = createSpeciality([
        'name' => 'Dermatology',
    ]);

    $first->delete();

    $result = $this->service->getAll();

    expect($result)
        ->toHaveCount(1);

    expect($result->pluck('id'))
        ->not->toContain($first->id);

    expect($result->pluck('id'))
        ->toContain($second->id);
});

/*
|--------------------------------------------------------------------------
| Correct Number Of Results
|--------------------------------------------------------------------------
*/

it('returns exactly the number of persisted specialities', function () {
    Speciality::factory()->count(10)->create();

    $result = $this->service->getAll();

    expect($result)
        ->toHaveCount(10);
});

/*
|--------------------------------------------------------------------------
| Foreign Key Irrelevance
|--------------------------------------------------------------------------
*/

it('does not require speciality relationships to retrieve records', function () {
    $speciality = createSpeciality();
    Cache::forget( 'speciality.all');
    $result = $this->service->getAll();

    expect($result->firstWhere('id', $speciality->id))
        ->not->toBeNull();
});