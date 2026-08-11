<?php

namespace App\Services\Doctor;

use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

class DoctorQueryService
{public function getAll(int $clinicId)
{
    $clinic = Clinic::with([
        'doctors' => function ($query) use ($clinicId) {
            $query->with([
                'specialities:id,name',
                'servicePrices.medicalService:id,name',
                'schedules.days:id,name',
                'media',
            ])->with([
                'clinics' => function ($query) use ($clinicId) {
                    $query->where('clinics.id', $clinicId);
                },
            ]);
        },
    ])->findOrFail($clinicId);

    return $clinic->doctors->map(function ($doctor) {
        $consultationFee = $doctor->servicePrices
            ->first(
                fn ($servicePrice) =>
                    $servicePrice->medicalService?->name === 'كشف'
            )
            ?->price;

        $clinic = $doctor->clinics->first();

        return [
            'id' => $doctor->id,
            'name' => $doctor->name,
            'phone' => $doctor->phone,
            'email' => $doctor->email,

            'speciality' => $doctor->specialities
                ->first()
                ?->only(['id', 'name']),

            'consultation_fee' => $consultationFee,

            'is_active' => $clinic?->pivot?->is_active,

            'schedules' => $doctor->schedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'slot_duration' => $schedule->slot_duration,
                    'start_break' => $schedule->start_break,
                    'end_break' => $schedule->end_break,
                    'is_available' => $schedule->is_available,

                    'days' => $schedule->days->map(fn ($day) => [
                        'id' => $day->id,
                        'name' => $day->name,
                    ])->values(),
                ];
            })->values(),

            'image' => $doctor->getFirstMediaUrl('avatar')
                ?: asset('storage/default_profile_image.jpg'),
        ];
    })->values();
}
    public function getDoctorsNames(?int $clinicId): Collection
    {
        if ($clinicId == null) {
            return new Collection([]);
        }

        return Doctor::select('id', 'name')
            ->whereRelation('clinics', 'clinic_id', $clinicId)->get();
    }

    public function getAvailableDoctors(int $clinicId, int $specialityId, int $serviceId): Collection
    {
        return Doctor::select('doctors.id', 'doctors.name')
            ->join('doctor_speciality', 'doctor_speciality.doctor_id', '=', 'doctors.id')
            ->join('doctor_service_prices', 'doctor_service_prices.doctor_id', '=', 'doctors.id')
            ->where('doctor_speciality.speciality_id', $specialityId)
            ->where('doctor_service_prices.medicalService_id', $serviceId)
            ->where('doctor_service_prices.clinic_id', $clinicId)
            ->distinct()
            ->get();
    }
}
