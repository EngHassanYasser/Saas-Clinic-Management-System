<?php

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use App\Models\User;
use App\Services\Clinic\ClinicService;
use Illuminate\Support\Facades\DB;

beforeEach(function () {
    $this->service = app(ClinicService::class);
});

it('deletes clinic successfully', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $result = $this->service->delete($clinic);

    expect($result)->toBeTrue();

    $this->assertDatabaseMissing('clinics', [
        'id' => $clinic->id,
    ]);
});

it('deletes clinic owner', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $this->service->delete($clinic);

    $this->assertDatabaseMissing('users', [
        'id' => $owner->id,
    ]);
});

it('deletes doctor service prices', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    Doctor_service_price::factory()->count(3)->create([
        'clinic_id' => $clinic->id,
    ]);

    expect(
        Doctor_service_price::count()
    )->toBe(3);

    $this->service->delete($clinic);

    expect(
        Doctor_service_price::count()
    )->toBe(0);
});

it('detaches all doctors from clinic', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    $doctors = Doctor::factory()
        ->count(3)
        ->create();

    $clinic->doctors()->attach(
        $doctors->pluck('id')->all()
    );

    expect(
        $clinic->doctors()->count()
    )->toBe(3);

    $this->service->delete($clinic);

    expect(
        DB::table('clinic_doctors')->count()
    )->toBe(0);
});

it('returns true after successful delete', function () {

    $owner = User::factory()->clinic()->create();

    $clinic = Clinic::factory()->create([
        'owner_id' => $owner->id,
    ]);

    expect(
        $this->service->delete($clinic)
    )->toBeTrue();
});