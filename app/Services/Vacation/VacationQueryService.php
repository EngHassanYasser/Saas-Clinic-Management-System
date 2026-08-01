<?php

namespace App\Services\Vacation;

use App\Models\Vication;
use Illuminate\Pagination\LengthAwarePaginator;

class VacationQueryService
{
    public function getClinicVacations(int $clinicId): LengthAwarePaginator
    {
        return Vication::select(
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
            ->with('doctor:id,name')->paginate(10);
    }
    public function getById() {}

    public function getDoctorVacations() {}

    public function getUpcomingVacations() {}
}
