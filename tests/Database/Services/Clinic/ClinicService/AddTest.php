<?php

use App\Enums\RoleType;
use App\Models\City;
use App\Models\Clinic;
use App\Models\User;
use App\Services\Clinic\ClinicService;
use Illuminate\Support\Facades\Hash;

beforeEach(function () {
    $this->service = app(ClinicService::class);
});

function clinicData(int $cityId): array
{
    return [
        'full_name'   => 'Ahmed Mohamed',
        'user_name'   => 'ahmed',
        'email'       => fake()->unique()->safeEmail(),
        'password'    => 'password',
        'gendor'      => 'male',
        'city_id'     => $cityId,
        'clinic_name' => 'Smile Clinic',
        'phone'       => '01000000000',
        'address'     => 'Cairo',
    ];
}

it('creates clinic and owner successfully', function () {

    $city = City::factory()->create();

    $data = clinicData($city->id);

    $clinic = $this->service->add($data);

    expect($clinic)->toBeInstanceOf(Clinic::class);

    $this->assertDatabaseHas('users', [
        'email' => $data['email'],
        'name'  => $data['full_name'],
        'type'  => 'clinic',
    ]);

    $this->assertDatabaseHas('clinics', [
        'name'     => $data['clinic_name'],
        'email'    => $data['email'],
        'owner_id' => $clinic->owner_id,
    ]);
});

it('hashes owner password', function () {

    $city = City::factory()->create();

    $data = clinicData($city->id);

    $this->service->add($data);

    $user = User::where('email', $data['email'])->first();

    expect(Hash::check($data['password'], $user->password))
        ->toBeTrue();

    expect($user->password)
        ->not
        ->toBe($data['password']);
});

it('generates clinic slug', function () {

    $city = City::factory()->create();

    $data = clinicData($city->id);

    $clinic = $this->service->add($data);

    expect($clinic->slug)
        ->toBe('smile-clinic');
});

it('links clinic with created owner', function () {

    $city = City::factory()->create();

    $data = clinicData($city->id);

    $clinic = $this->service->add($data);

    $owner = User::find($clinic->owner_id);

    expect($owner)->not->toBeNull();

    expect($owner->type)
        ->toBe(RoleType::CLINIC);

    expect($owner->id)
        ->toBe($clinic->owner_id);
});

it('stores city for owner and clinic', function () {

    $city = City::factory()->create();

    $data = clinicData($city->id);

    $clinic = $this->service->add($data);

    expect($clinic->city_id)
        ->toBe($city->id);

    expect($clinic->owner->city_id)
        ->toBe($city->id);
});