<?php

namespace App\services;

use App\Models\Clinic;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClinicService
{
    public function add(array $data): Clinic
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['full_name'],
                'user_name' => $data['user_name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'gendor' => $data['gendor'],
                'city_id' => $data['city_id'],
                'type' => 'clinic',
            ]);
            $clinic = clinic::create([
                'name' => $data['clinic_name'],
                'slug' =>  Str::slug($data['clinic_name']),
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
                'owner_id' => $user->id,
                'city_id' => $data['city_id'],
            ]);
            return $clinic;
        });
    }
    public function update(array $data, Clinic $clinic): bool
    {
        return DB::transaction(function () use ($data, $clinic) {

            $clinic->load('owner');

            $clinic->update([
                'name' => $data['clinic_name'],
                'slug' => Str::slug($data['clinic_name']),
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
                'city_id' => $data['city_id'],
            ]);

            $userData = [
                'name' => $data['full_name'],
                'user_name' => $data['user_name'],
                'gendor' => $data['gendor'],
            ];

            if (!empty($data['password'])) {
                $userData['password'] = Hash::make($data['password']);
            }

            return $clinic->owner->update($userData);
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
