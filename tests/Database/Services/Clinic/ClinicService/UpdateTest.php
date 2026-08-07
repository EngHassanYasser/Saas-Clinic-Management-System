<?php

use App\Models\City;
use App\Models\Clinic;
use App\Models\Day;
use App\Models\User;
use App\Services\Clinic\ClinicService;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->service = app(ClinicService::class);
});

function updateData(Clinic $clinic): array
{
    return [
        'name' => 'Updated Clinic',
        'phone' => '01111111111',
        'email' => 'updated@test.com',
        'address' => 'Giza',
        'city_id' => City::factory()->create()->id,
        'password' => '',
        'work_days' => [],
        'open_time' => '08:00:00',
        'close_time' => '16:00:00',
    ];
}

it('updates clinic information', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $data = updateData($clinic);

    $result = $this->service->update($data, $clinic);

    expect($result)->toBeTrue();

    $clinic->refresh();

    expect($clinic->name)->toBe($data['name'])
        ->and($clinic->slug)->toBe('updated-clinic')
        ->and($clinic->phone)->toBe($data['phone'])
        ->and($clinic->email)->toBe($data['email'])
        ->and($clinic->address)->toBe($data['address'])
        ->and($clinic->city_id)->toBe($data['city_id'])
        ->and($clinic->open_time)->toBe($data['open_time'])
        ->and($clinic->close_time)->toBe($data['close_time']);
});

it('updates owner password', function () {

    $owner = User::factory()->clinic()->create([
        'password' => Hash::make('old-password'),
    ]);

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $data = updateData($clinic);

    $data['password'] = 'new-password';

    $this->service->update($data, $clinic);

    $owner->refresh();

    expect(Hash::check('new-password', $owner->password))
        ->toBeTrue();
});

it('does not update password when password is empty', function () {

    $owner = User::factory()->clinic()->create([
        'password' => Hash::make('old-password'),
    ]);

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $oldPassword = $owner->password;

    $data = updateData($clinic);

    $data['password'] = '';

    $this->service->update($data, $clinic);

    $owner->refresh();

    expect($owner->password)->toBe($oldPassword);
});

it('syncs work days', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $days = Day::factory()->count(3)->create();

    $data = updateData($clinic);

    $data['work_days'] = $days->pluck('id')->toArray();

    $this->service->update($data, $clinic);

    expect($clinic->fresh()->days)
        ->toHaveCount(3);

    expect(
        $clinic->fresh()->days->pluck('id')->sort()->values()->all()
    )->toBe(
        $days->pluck('id')->sort()->values()->all()
    );
});

it('returns true when clinic is updated successfully', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $result = $this->service->update(
        updateData($clinic),
        $clinic
    );

    expect($result)->toBeTrue();
});
