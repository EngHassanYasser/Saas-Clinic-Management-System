<?php

namespace App\Services;

use App\Models\Clinic;
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
                    'phone'=>$clinic->phone,
                    'email' => $clinic->email,
                    'status' => $clinic->latestSubscription?->status,
                    'city' => $clinic->city,
                    'plan' => $clinic->latestSubscription?->plan,
                    'joined_at' => $clinic->created_at->toDateString(),
                    'owner' => $clinic->owner,
                    'address'=>$clinic->address
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
                'city_id'=>$data['city_id'],
                'type' => 'clinic',
            ]);
            $slug = Str::slug($data['clinic_name']);
            clinic::create([
                'name' => $data['clinic_name'],
                'slug' => $slug,
                'phone' => $data['phone'],
                'email' => $data['email'],
                'address' => $data['address'],
                'owner_id' => $user->id,
                'city_id'=>$data['city_id'],
            ]);
            return $user;
        });
    }
}
