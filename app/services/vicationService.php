<?php
namespace App\services;

use App\Models\vication;


class vicationService
{
    public function getClinicVacations($clinicId)
    {
        return vication::select(
            'id',
            'start_date',
            'end_date',
            'reason',
            'doctor_id',
            'status',
        )
            ->whereHas('doctor.clinics', function ($query) use ($clinicId) {
                $query->where('clinics.id', $clinicId);
            })
            ->with('doctor:id,name')
            ->paginate(10);
    }
}
