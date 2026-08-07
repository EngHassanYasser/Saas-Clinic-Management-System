<?php

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\ActivityLog\ActivityLogQueryService;

beforeEach(function () {
    $this->service = app(ActivityLogQueryService::class);
});


it('returns a length aware paginator', function () {
    ActivityLog::factory()->count(3)->create();

    $result = $this->service->getLastActivities();

    expect($result)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class);
});


it('returns activities from the database', function () {
    ActivityLog::factory()->count(5)->create();

    $result = $this->service->getLastActivities();

    expect($result->total())->toBe(5)
        ->and($result->count())->toBe(5);
});


it('paginates activities with five records per page', function () {
    ActivityLog::factory()->count(12)->create();

    $result = $this->service->getLastActivities();

    expect($result->perPage())->toBe(5)
        ->and($result->total())->toBe(12)
        ->and($result->count())->toBe(5);
});


it('returns the correct number of pages', function () {
    ActivityLog::factory()->count(12)->create();

    $result = $this->service->getLastActivities();

    expect($result->lastPage())->toBe(3);
});


it('returns all records when total records are less than five', function () {
    ActivityLog::factory()->count(3)->create();

    $result = $this->service->getLastActivities();

    expect($result->total())->toBe(3)
        ->and($result->count())->toBe(3)
        ->and($result->lastPage())->toBe(1);
});


it('returns an empty paginator when there are no activities', function () {
    $result = $this->service->getLastActivities();

    expect($result->total())->toBe(0)
        ->and($result->count())->toBe(0)
        ->and($result->lastPage())->toBe(1);
});


it('returns the latest activities first', function () {
    $oldest = ActivityLog::factory()->create([
        'created_at' => now()->subDays(3),
    ]);

    $middle = ActivityLog::factory()->create([
        'created_at' => now()->subDay(),
    ]);

    $latest = ActivityLog::factory()->create([
        'created_at' => now(),
    ]);

    $result = $this->service->getLastActivities();

    expect($result->items()[0]->id)->toBe($latest->id)
        ->and($result->items()[1]->id)->toBe($middle->id)
        ->and($result->items()[2]->id)->toBe($oldest->id);
});


it('returns only five latest activities on the first page', function () {
    $activities = ActivityLog::factory()
        ->count(8)
        ->sequence(fn ($sequence) => [
            'created_at' => now()->subDays($sequence->index),
        ])
        ->create();

    $result = $this->service->getLastActivities();

    expect($result->count())->toBe(5);

    $returnedIds = collect($result->items())
        ->pluck('id')
        ->all();

    $expectedIds = ActivityLog::query()
        ->latest('created_at')
        ->limit(5)
        ->pluck('id')
        ->all();

    expect($returnedIds)->toBe($expectedIds);
});


it('returns the second page correctly', function () {
    ActivityLog::factory()
        ->count(12)
        ->sequence(fn ($sequence) => [
            'created_at' => now()->subDays($sequence->index),
        ])
        ->create();

    request()->merge([
        'page' => 2,
    ]);

    $result = $this->service->getLastActivities();

    expect($result->currentPage())->toBe(2)
        ->and($result->perPage())->toBe(5)
        ->and($result->count())->toBe(5);
});


it('returns the remaining records on the last page', function () {
    ActivityLog::factory()->count(12)->create();

    request()->merge([
        'page' => 3,
    ]);

    $result = $this->service->getLastActivities();

    expect($result->currentPage())->toBe(3)
        ->and($result->count())->toBe(2);
});


it('does not return more than five activities per page', function () {
    ActivityLog::factory()->count(100)->create();

    $result = $this->service->getLastActivities();

    expect($result->count())->toBe(5)
        ->and($result->perPage())->toBe(5)
        ->and($result->total())->toBe(100);
});


it('returns the expected activity attributes', function () {
    $user = User::factory()->create();

    $activity = ActivityLog::factory()->create([
        'type' => 'appointment.updated',
        'title' => 'Appointment Updated',
        'description' => 'Appointment status was changed',
        'status' => 'success',
        'subject_type' => 'Appointment',
        'subject_id' => 123,
        'created_by' => $user->id,
    ]);

    $result = $this->service->getLastActivities();

    $returnedActivity = $result->first();

    expect($returnedActivity->id)->toBe($activity->id)
        ->and($returnedActivity->type)->toBe('appointment.updated')
        ->and($returnedActivity->title)->toBe('Appointment Updated')
        ->and($returnedActivity->description)->toBe('Appointment status was changed')
        ->and($returnedActivity->status)->toBe('success')
        ->and($returnedActivity->subject_type)->toBe('Appointment')
        ->and($returnedActivity->subject_id)->toBe(123)
        ->and($returnedActivity->created_by)->toBe($user->id)
        ->and($returnedActivity->created_at)->not->toBeNull();
});


it('does not select columns that are not part of the activity response', function () {
    $activity = ActivityLog::factory()->create();

    $result = $this->service->getLastActivities();

    $returnedActivity = $result->first();

    expect(array_keys($returnedActivity->getAttributes()))
        ->toBe([
            'id',
            'type',
            'title',
            'description',
            'status',
            'subject_type',
            'subject_id',
            'created_by',
            'created_at',
        ]);
});


it('does not modify the activity records', function () {
    $activity = ActivityLog::factory()->create([
        'title' => 'Original title',
        'status' => 'success',
    ]);

    $this->service->getLastActivities();

    $activity->refresh();

    expect($activity->title)->toBe('Original title')
        ->and($activity->status)->toBe('success');
});


it('does not create new activity records', function () {
    ActivityLog::factory()->count(5)->create();

    $before = ActivityLog::count();

    $this->service->getLastActivities();

    expect(ActivityLog::count())->toBe($before);
});


it('keeps the total count independent from pagination', function () {
    ActivityLog::factory()->count(17)->create();

    $result = $this->service->getLastActivities();

    expect($result->total())->toBe(17)
        ->and($result->count())->toBe(5)
        ->and($result->lastPage())->toBe(4);
});
