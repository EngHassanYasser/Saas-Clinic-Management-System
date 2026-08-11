<?php

namespace App\Services\Clinic;

use App\DTOs\Services\Clinic\StoreClinicDTO;
use App\DTOs\Services\Clinic\UpdateClinicDTO;
use App\Enums\EnRoleType;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClinicService
{
    public function add(StoreClinicDTO $dto): Clinic
    {
        return DB::transaction(function () use ($dto) {
            $user = User::create([
                'name' => $dto->name,
                'user_name' => $dto->username,
                'email' => $dto->email,
                'password' => Hash::make($dto->password),
                'gendor' => $dto->gendor,
                'city_id' => $dto->cityId,
                'type' => EnRoleType::CLINIC->value,
            ]);
            $clinic = Clinic::create([
                'name' => $dto->name,
                'slug' => Str::slug($dto->name),
                'phone' => $dto->phone,
                'email' => $dto->email,
                'address' => $dto->address,
                'owner_id' => $user->id,
                'city_id' => $dto->cityId,
            ]);

            return $clinic;
        });
    }

    public function update(UpdateClinicDTO $dto, Clinic $clinic): bool
    {
        return DB::transaction(function () use ($dto, $clinic) {

            $clinic->load('owner');

            if ($dto->password) {
                $clinic->owner->update([
                    'password' => Hash::make($dto->password),
                ]);
            }
            if ($dto->logo) {
                $clinic->addMedia($dto->logo)
                    ->toMediaCollection('logo');
            }
            $clinic->days()->sync($dto->workDays);

            return $clinic->update([
                'name' => $dto->name,
                'slug' => Str::slug($dto->name),
                'phone' => $dto->phone,
                'email' => $dto->email,
                'address' => $dto->address,
                'city_id' => $dto->cityId,
                'open_time' => $dto->openTime,
                'close_time' => $dto->closeTime,
            ]);
        });
    }

    public function delete(Clinic $clinic): bool
    {
        return DB::transaction(function () use ($clinic) {
            $clinic->doctors()->detach();
            $clinic->servicePrices()->delete();
            $owner_id = $clinic->owner_id;
            $clinic->delete();

            return User::where('id', $owner_id)->delete();
        });
    }
}
