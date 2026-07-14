<?php

namespace App\Services;

use App\Models\Complain;
use Illuminate\Support\Facades\Auth;

class ComplainService
{
    public function getClinicComplains($clinic_id)
    {
        return Complain::select(
            'id',
            'clinic_id',
            'user_id',
            'doctor_id',
            'issue_type',
            'severity',
            'description',
            'visit_date',
            'status',
            'department',
            'resolution_notes',
            'resolved_at',
            'updated_at',
            'created_at'
        )->where('clinic_id', $clinic_id)
            ->with(['patient:id,name','doctor:id,name'])
            ->get();
    }
    public function getStatistics()
    {
        $stats = Complain::where('clinic_id', Auth::user()->clinic_id)
            ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'under_review' THEN 1 ELSE 0 END) as under_review,
        SUM(CASE WHEN status = 'resolved' THEN 1 ELSE 0 END) as resolved,
        SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
    ")->first();
        return $stats;
    }
}
