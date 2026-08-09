<?php

namespace App\Services\Clinic;

use App\Models\Clinic;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Models\DoctorService as ModelDoctorService;

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
            'open_time',
            'close_time',
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
            ->join('doctorServices', 'doctorServices.speciality_id', '=', 'doctor_speciality.speciality_id')
            ->where('clinic_doctors.is_active', true)
            ->where('doctorServices.speciality_id', $specialityId)
            ->where('doctorServices.id', $serviceId)
            ->distinct()
            ->get();
    }
    public function getDoctorServicesBySpecialityId(int $specialityId): Collection
    {
        return ModelDoctorService::where('speciality_id', $specialityId)->select(['id', 'name'])->get();
    }
    public function getClinicByOwnereId($ownerId): Clinic
    {
        $clinic = Clinic::where('owner_id', $ownerId)
            ->with(['city','days'])
            ->firstOrFail();
        $clinic->logo = $clinic->getFirstMediaUrl('logo');

        return $clinic;
    }
}
