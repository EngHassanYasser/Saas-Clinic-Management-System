<?php

namespace App\Services;

use App\Models\Clinic;
use App\Models\subscription;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ClinicService
{
    public function getAll()
    {
        return Clinic::select([
            'id',
            'name',
            'email',
            'phone',
            'address',
            'created_at',
            'city_id',
            'owner_id',
        ])
            ->with([
                'owner:id,name,user_name,gendor',
                'city:id,name',
                'latestSubscription',
                'latestSubscription.plan:id,name,monthly_price',
            ])
            ->paginate(5)
            ->through(function ($clinic) {
                return [
                    'id' => $clinic->id,
                    'name' => $clinic->name,
                    'phone' => $clinic->phone,
                    'email' => $clinic->email,
                    'status' => $clinic->latestSubscription?->status,
                    'city' => $clinic->city,
                    'plan' => $clinic->latestSubscription?->plan,
                    'joined_at' => $clinic->created_at->toDateString(),
                    'owner' => $clinic->owner,
                    'address' => $clinic->address
                ];
            });
    }
    public function add($data)
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
            clinic::create([
                'name' => $data['clinic_name'],
                'slug' =>  Str::slug($data['clinic_name']),
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
                'owner_id' => $user->id,
                'city_id' => $data['city_id'],
            ]);
            return $user;
        });
    }

    public function update(array $data, Clinic $clinic): void
    {
        DB::transaction(function () use ($data, $clinic) {

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

            $clinic->owner->update($userData);
        });
    }

    public function delete(Clinic $clinic)
    {
        return DB::transaction(function () use ($clinic) {
            $clinic->doctors()->detach();
            $clinic->servicePrices()->delete();
            $owner_id = $clinic->owner_id;
            $clinic->delete();
            return user::where('id', $owner_id)->delete();
        });
    }
    public function getStats()
    {
        return Subscription::query()
            ->selectRaw("
        COUNT(*) as total,
        COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as pending,
        COALESCE(SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END), 0) as active,
COALESCE(SUM(CASE WHEN status = 'cancelled' OR status = 'expired' THEN 1 ELSE 0 END),0) AS inactive    ")->whereIn('id', function ($query) {
                $query->selectRaw('MAX(id)')
                    ->from('subscriptions')
                    ->whereNotNull('clinic_id')
                    ->groupBy('clinic_id');
            })->first();
    }
}
