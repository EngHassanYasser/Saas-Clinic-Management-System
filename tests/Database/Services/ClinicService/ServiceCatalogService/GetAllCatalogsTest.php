<?php

use App\Models\DoctorService;
use App\Services\ServiceCatalog\ServiceCatalogService;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = app(ServiceCatalogService::class);
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function createCatalog(array $overrides = []): DoctorService
{
    return DoctorService::factory()->create($overrides);
}

/*
|--------------------------------------------------------------------------
| Return type
|--------------------------------------------------------------------------
*/

it('returns an array', function () {
    createCatalog();

    $result = $this->service->getAllCatalogs();

    expect($result)
        ->toBeArray();
});
it('returns an array of clinic service data', function () {
    createCatalog();

    $result = $this->service->getAllCatalogs();

    expect($result)
        ->toBeArray()
        ->each->toBeArray();
});

/*
|--------------------------------------------------------------------------
| Empty database
|--------------------------------------------------------------------------
*/

it('returns an empty collection when there are no clinic services', function () {
    $result = $this->service->getAllCatalogs();

    expect($result)
        ->toBeEmpty()
        ->toHaveCount(0);
});

/*
|--------------------------------------------------------------------------
| Get all records
|--------------------------------------------------------------------------
*/

it('returns all clinic services', function () {
    $first = createCatalog();
    $second = createCatalog();
    $third = createCatalog();

    $result = collect($this->service->getAllCatalogs());

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
it('returns each database record exactly once', function () {
    $first = createCatalog();
    $second = createCatalog();
    $third = createCatalog();
    $fourth = createCatalog();

    $result = collect($this->service->getAllCatalogs());

    $ids = $result->pluck('id');

    expect($ids)
        ->toHaveCount(4)
        ->and($ids->unique()->count())
        ->toBe(4);

    expect($ids)
        ->toContain(
            $first->id,
            $second->id,
            $third->id,
            $fourth->id
        );
});

/*
|--------------------------------------------------------------------------
| Correct model
|--------------------------------------------------------------------------
*/

it('returns clinic service data as arrays', function () {
    createCatalog();

    $result = $this->service->getAllCatalogs();

    expect($result)
        ->toBeArray()
        ->each->toBeArray();
});

/*
|--------------------------------------------------------------------------
| Selected columns
|--------------------------------------------------------------------------
*/

it('returns the required columns', function () {
    $catalog = createCatalog();

    $result = collect($this->service->getAllCatalogs());

    $item = $result->firstWhere('id', $catalog->id);

    expect($item)
        ->toHaveKeys([
            'id',
            'name',
            'speciality_id',
        ]);
});
it('does not select unnecessary columns', function () {
    $catalog = createCatalog();

    $result = collect($this->service->getAllCatalogs());

    $item = $result->firstWhere('id', $catalog->id);

    expect(array_keys($item))
        ->toBe([
            'id',
            'name',
            'speciality_id',
        ]);
});
/*
|--------------------------------------------------------------------------
| Correct values
|--------------------------------------------------------------------------
*/

it('returns the correct id', function () {
    $catalog = createCatalog();

    $result = collect($this->service->getAllCatalogs());

    $item = $result->firstWhere('id', $catalog->id);

    expect($item['id'])
        ->toBe($catalog->id);
});

it('returns the correct name', function () {
    $catalog = createCatalog([
        'name' => 'Cardiology Consultation',
    ]);

    $result = collect($this->service->getAllCatalogs());

    $item = $result->firstWhere('id', $catalog->id);

    expect($item['name'])
        ->toBe('Cardiology Consultation');
});
it('returns the correct speciality id', function () {
    $catalog = createCatalog();

    $result = collect($this->service->getAllCatalogs());

    $item = $result->firstWhere('id', $catalog->id);

    expect($item['speciality_id'])
        ->toBe($catalog->speciality_id);
});
/*
|--------------------------------------------------------------------------
| Multiple records values
|--------------------------------------------------------------------------
*/

it('returns correct values for every clinic service', function () {
    $first = createCatalog([
        'name' => 'First Service',
    ]);

    $second = createCatalog([
        'name' => 'Second Service',
    ]);

    $third = createCatalog([
        'name' => 'Third Service',
    ]);

    $result = collect($this->service->getAllCatalogs());

    $firstResult = $result->firstWhere('id', $first->id);
    $secondResult = $result->firstWhere('id', $second->id);
    $thirdResult = $result->firstWhere('id', $third->id);

    expect($firstResult['name'])
        ->toBe('First Service');

    expect($secondResult['name'])
        ->toBe('Second Service');

    expect($thirdResult['name'])
        ->toBe('Third Service');

    expect($firstResult['speciality_id'])
        ->toBe($first->speciality_id);

    expect($secondResult['speciality_id'])
        ->toBe($second->speciality_id);

    expect($thirdResult['speciality_id'])
        ->toBe($third->speciality_id);
});
/*
|--------------------------------------------------------------------------
| Database persistence
|--------------------------------------------------------------------------
*/

it('returns records that actually exist in the database', function () {
    $catalog = createCatalog();

    $result = collect($this->service->getAllCatalogs());

    expect(
        $result->contains(
            fn (array $item) => $item['id'] === $catalog->id
        )
    )->toBeTrue();

    expect(
        DB::table('doctorServices')
            ->where('id', $catalog->id)
            ->exists()
    )->toBeTrue();
});
/*
|--------------------------------------------------------------------------
| Does not mutate database
|--------------------------------------------------------------------------
*/

it('does not modify the database', function () {
    createCatalog();
    createCatalog();
    createCatalog();

    $beforeCount = DoctorService::count();

    $this->service->getAllCatalogs();

    expect(DoctorService::count())
        ->toBe($beforeCount);
});

it('does not delete any records', function () {
    $first = createCatalog();
    $second = createCatalog();

    $this->service->getAllCatalogs();

    expect(
        DoctorService::whereKey($first->id)->exists()
    )->toBeTrue();

    expect(
        DoctorService::whereKey($second->id)->exists()
    )->toBeTrue();
});

/*
|--------------------------------------------------------------------------
| Query count
|--------------------------------------------------------------------------
*/

it('executes exactly one query', function () {
    createCatalog();
    createCatalog();
    createCatalog();

    DB::enableQueryLog();

    $this->service->getAllCatalogs();

    $queries = DB::getQueryLog();

    DB::disableQueryLog();

    expect($queries)
        ->toHaveCount(1);
});

/*
|--------------------------------------------------------------------------
| Query does not depend on record count
|--------------------------------------------------------------------------
*/

it('keeps query count constant regardless of number of records', function () {
        Cache::forget('clinicService.all');

    createCatalog();
    DB::enableQueryLog();
    DB::flushQueryLog();

    $result = $this->service->getAllCatalogs();

    $queriesForOneRecord = count(DB::getQueryLog());

    DB::disableQueryLog();

    createCatalog();
    createCatalog();
    createCatalog();
    createCatalog();

    DB::enableQueryLog();
    DB::flushQueryLog();
    Cache::forget('clinicService.all');

    $result = $this->service->getAllCatalogs();

    $queriesForFiveRecords = count(DB::getQueryLog());

    DB::disableQueryLog();

    expect($result)
        ->toHaveCount(5);

    expect($queriesForOneRecord)
        ->toBe(1);

    expect($queriesForFiveRecords)
        ->toBe(1);
});

/*
|--------------------------------------------------------------------------
| No N+1
|--------------------------------------------------------------------------
*/

it('does not execute additional queries when accessing selected attributes', function () {
    createCatalog();
    createCatalog();
    createCatalog();
    createCatalog();
    createCatalog();

    DB::enableQueryLog();

    $result = $this->service->getAllCatalogs();

    foreach ($result as $item) {
        $item['id'];
        $item['name'];
        $item['speciality_id'];
    }

    $queries = DB::getQueryLog();

    DB::disableQueryLog();

    expect($queries)
        ->toHaveCount(1);
});

/*
|--------------------------------------------------------------------------
| Calling method multiple times
|--------------------------------------------------------------------------
*/

it('can be called multiple times consistently', function () {
    $first = createCatalog();
    $second = createCatalog();

    $firstResult = collect($this->service->getAllCatalogs());
    $secondResult = collect($this->service->getAllCatalogs());

    expect($firstResult)
        ->toHaveCount(2);

    expect($secondResult)
        ->toHaveCount(2);

    expect(
        $firstResult->pluck('id')->sort()->values()->all()
    )->toBe(
        $secondResult->pluck('id')->sort()->values()->all()
    );
});
/*
|--------------------------------------------------------------------------
| New records are reflected
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Special characters
|--------------------------------------------------------------------------
*/

it('returns names containing special characters correctly', function () {
    $catalog = createCatalog([
        'name' => 'كشف وعلاج الأسنان - د. أحمد',
    ]);

    $result = collect($this->service->getAllCatalogs());

    $item = $result->firstWhere('id', $catalog->id);

    expect($item['name'])
        ->toBe('كشف وعلاج الأسنان - د. أحمد');
});
/*
|--------------------------------------------------------------------------
| Long names
|--------------------------------------------------------------------------
*/

it('returns long service names correctly', function () {
    $name = str_repeat('Medical Service ', 10);

    $catalog = createCatalog([
        'name' => $name,
    ]);

    $result = collect($this->service->getAllCatalogs());

    $item = $result->firstWhere('id', $catalog->id);

    expect($item['name'])
        ->toBe($name);
});
/*
|--------------------------------------------------------------------------
| Same speciality
|--------------------------------------------------------------------------
*/

it('correctly handles multiple services belonging to the same speciality', function () {
    $first = createCatalog();

    $second = createCatalog([
        'speciality_id' => $first->speciality_id,
    ]);

    $third = createCatalog([
        'speciality_id' => $first->speciality_id,
    ]);

    $result = collect($this->service->getAllCatalogs());

    expect($result)
        ->toHaveCount(3);

    expect(
        $result->firstWhere('id', $first->id)['speciality_id']
    )->toBe($first->speciality_id);

    expect(
        $result->firstWhere('id', $second->id)['speciality_id']
    )->toBe($first->speciality_id);

    expect(
        $result->firstWhere('id', $third->id)['speciality_id']
    )->toBe($first->speciality_id);
});
/*
|--------------------------------------------------------------------------
| Different specialities
|--------------------------------------------------------------------------
*/

it('correctly handles services from different specialities', function () {
    $first = createCatalog();
    $second = createCatalog();
    $third = createCatalog();

    $result = $this->service->getAllCatalogs();

    $firstItem = collect($result)->firstWhere('id', $first->id);
    $secondItem = collect($result)->firstWhere('id', $second->id);
    $thirdItem = collect($result)->firstWhere('id', $third->id);

    expect($firstItem['speciality_id'])
        ->toBe($first->speciality_id);

    expect($secondItem['speciality_id'])
        ->toBe($second->speciality_id);

    expect($thirdItem['speciality_id'])
        ->toBe($third->speciality_id);
});
/*
|--------------------------------------------------------------------------
| Does not eager load unnecessary relationships
|--------------------------------------------------------------------------
*/

it('returns only required clinic service columns', function () {
    createCatalog();

    $result = $this->service->getAllCatalogs();

    expect($result)->not->toBeEmpty();

    $item = collect($result)->first();

    expect($item)
        ->toHaveKeys([
            'id',
            'name',
            'speciality_id',
        ])
        ->not->toHaveKeys([
            'created_at',
            'updated_at',
        ]);
});

/*
|--------------------------------------------------------------------------
| Correct database values
|--------------------------------------------------------------------------
*/

it('matches the database values exactly', function () {
    $catalog = createCatalog();

    $databaseRow = DB::table('doctorServices')
        ->where('id', $catalog->id)
        ->first();

    $result = $this->service->getAllCatalogs();

    $item = collect($result)->firstWhere('id', $catalog->id);

    expect($item['id'])
        ->toBe($databaseRow->id);

    expect($item['name'])
        ->toBe($databaseRow->name);

    expect($item['speciality_id'])
        ->toBe($databaseRow->speciality_id);
});

/*
|--------------------------------------------------------------------------
| Large dataset
|--------------------------------------------------------------------------
*/
it('handles many clinic services correctly', function () {
    DoctorService::factory()
        ->count(100)
        ->create();

    $result = $this->service->getAllCatalogs();

    expect($result)
        ->toBeArray()
        ->toHaveCount(100);

    expect(collect($result)->pluck('id')->unique()->count())
        ->toBe(100);
});