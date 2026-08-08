<?php

namespace App\Services\Doctor;

use App\Models\Doctor;
use Illuminate\Support\Facades\Cache;

class DoctorStatisticsService
{
    public function getStats(int $clinicId): object
    {
        return Cache::remember(
            "doctors.statistics.clinic.{$clinicId}",
            now()->addMinutes(5),
            function () use ($clinicId) {
                $statistics = Doctor::query()
                    ->join(
                        'clinic_doctors',
                        'doctors.id',
                        '=',
                        'clinic_doctors.doctor_id'
                    )
                    ->leftJoin(
                        'doctor_speciality',
                        'doctors.id',
                        '=',
                        'doctor_speciality.doctor_id'
                    )
                    ->where('clinic_doctors.clinic_id', $clinicId)
                    ->selectRaw('
                    COUNT(DISTINCT doctors.id) as total,
                    COUNT(
                        DISTINCT CASE
                            WHEN clinic_doctors.is_active = 1
                            THEN doctors.id
                        END
                    ) as active,
                    COUNT(
                        DISTINCT CASE
                            WHEN clinic_doctors.is_active = 0
                            THEN doctors.id
                        END
                    ) as inactive,
                    COUNT(
                        DISTINCT doctor_speciality.speciality_id
                    ) as specialities
                ')
                    ->first();

                foreach ([
                    'total',
                    'active',
                    'inactive',
                    'specialities',
                ] as $field) {
                    $statistics->{$field} = (int) $statistics->{$field};
                }

                return $statistics;
            }
        );
    }
}
