<?php

namespace App\Services\Clinic;

use App\DTOs\Services\Clinic\ClinicService\StoreClinicDTO;
use App\DTOs\Services\Clinic\ClinicService\UpdateClinicDTO;
use App\Enums\RoleType;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DoctorService
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
                'type' => RoleType::CLINIC->value,
            ]);
            $clinic = clinic::create([
                'name' => $dto->name,
                'slug' =>  Str::slug($dto->name),
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

            if (!empty($data['password'])) {
                $clinic->owner->update(['password' => Hash::make($data['password'])]);
            }
            if (isset($data['logo'])) {
                $clinic
                    ->addMedia($dto->logo)
                    ->toMediaCollection('logo');
            }
            $clinic->days()->sync($dto->workDays);
            return  $clinic->update([
                'name' => $dto->name,
                'slug' => Str::slug($dto->name),
                'phone' => $dto->phone,
                'email' => $dto->email,
                'address' => $dto->address,
                'city_id' => $dto->cityId,
                'open_time'=>$dto->openTime,
                'close_time'=>$dto->closeTime,
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
            return user::where('id', $owner_id)->delete();
        });
    }
}
