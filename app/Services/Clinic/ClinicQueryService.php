<?php

namespace App\Services\Clinic;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\ClinicService as ModelClinicService;

class ClinicQueryService
{
    public function getAll(): LengthAwarePaginator
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
    public function getAvailableClinics(int $specialityId, int $serviceId): Collection
    {
        return Clinic::select('clinics.id', 'clinics.name', 'cities.name as city_name')
            ->join('cities', 'cities.id', '=', 'clinics.city_id')
            ->join('clinic_doctors', 'clinic_doctors.clinic_id', '=', 'clinics.id')
            ->join('doctor_speciality', 'doctor_speciality.doctor_id', '=', 'clinic_doctors.doctor_id')
            ->join('clinic_services', 'clinic_services.speciality_id', '=', 'doctor_speciality.speciality_id')
            ->where('clinic_doctors.is_active', true)
            ->where('clinic_services.speciality_id', $specialityId)
            ->where('clinic_services.id', $serviceId)
            ->distinct()
            ->get();
    }
    public function getClinicServicesBySpecialityId(int $specialityId): Collection
    {
        return ModelClinicService::where('speciality_id', $specialityId)->select(['id', 'name'])->get();
    }
    public function getClinicByOwnereId($ownerId):Clinic {
        return Clinic::where('owner_id',$ownerId)->firstOrFail();
    }
}
