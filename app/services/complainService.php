<?php

namespace App\Services;

use App\Models\Complain;

class ComplainService
{
    public function getClinicComplains($clinic_id)
    {
        return Complain::select(
            'id',
            'clinic_id',
            'user_id',
            'appointment_id',
            'target_type',
            'target_id',
            'subject',
            'description',
            'category',
            'status',
            'resolution_notes',
            'resolved_at',
            'updated_at',
            'created_at'
        )->where('clinic_id',$clinic_id)
        ->with(['appointment', 'clinic', 'patient'])
        ->get();
    }
}