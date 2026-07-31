<?php

namespace App\Services;

use App\Models\Doctor;

class DoctorStatisticsService
{
    public function getStats(int $clinicId): Doctor
    {
        return Doctor::query()
            ->join('clinic_doctors', 'doctors.id', '=', 'clinic_doctors.doctor_id')
            ->leftJoin('doctor_speciality', 'doctors.id', '=', 'doctor_speciality.doctor_id')
            ->where('clinic_doctors.clinic_id', $clinicId)
            ->selectRaw("
        COUNT(DISTINCT doctors.id) as total,
        COUNT(DISTINCT CASE WHEN clinic_doctors.is_active = 1 THEN doctors.id END) as active,
        COUNT(DISTINCT CASE WHEN clinic_doctors.is_active = 0 THEN doctors.id END) as inactive,
        COUNT(DISTINCT doctor_speciality.speciality_id) as specialities
        ")->first();
    }
}
