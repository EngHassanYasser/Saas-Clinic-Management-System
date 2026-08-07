<?php

namespace App\Services\Doctor;

use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

class DoctorQueryService
{
    public function getAll(int $clinicId)
    {
        $clinic = Clinic::with([
            'doctors.specialities',
            'doctors.servicePrices.clinic_service',
            'schedules',
            'doctors.media',
        ])->findOrFail($clinicId);

        return $clinic->doctors->map(function ($doctor) use ($clinic) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'phone' => $doctor->phone,
                'email' => $doctor->email,
                $consultationFee = optional(
                    $doctor->servicePrices
                        ->first(fn ($item) => $item->clinic_service?->name === 'كشف')
                )->price,

                'Consultation_Fee' => $consultationFee !== null
                    ? $consultationFee
                    : 'لا توجد خدمة',
                'speciality' => optional($doctor->specialities->first())
                    ->only(['id', 'name']),

                'is_active' => $doctor->clinics
                    ->firstWhere('id', $clinic->id)
                    ?->pivot
                    ?->is_active,
                'schedules' => $doctor->schedules,
                'image' => $doctor->getFirstMediaUrl('avatar')
                    ?: asset('storage/default_profile_image.jpg'),
            ];
        });
    }

    public function getDoctorsNames(int $clinicId): Collection
    {
        return Doctor::select('id', 'name')
            ->whereRelation('clinics', 'clinic_id', $clinicId)->get();
    }

    public function getAvailableDoctors(int $clinicId, int $specialityId, int $serviceId): Collection
    {
        return Doctor::select('doctors.id', 'doctors.name')
            ->join('doctor_speciality', 'doctor_speciality.doctor_id', '=', 'doctors.id')
            ->join('doctor_service_prices', 'doctor_service_prices.doctor_id', '=', 'doctors.id')
            ->where('doctor_speciality.speciality_id', $specialityId)
            ->where('doctor_service_prices.clinic_service_id', $serviceId)
            ->where('doctor_service_prices.clinic_id', $clinicId)
            ->distinct()
            ->get();
    }
}
