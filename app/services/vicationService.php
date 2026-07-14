<?php

namespace App\services;

use App\Exceptions\hasVicationException;
use App\Models\vication;
use Illuminate\Support\Facades\Auth;

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
    public function update(array $data, int $vicationId): bool
    {
        $hasVication = $this->hasVacation($data['doctor_id'], $vicationId);
        if ($hasVication) {
            throw new HasVicationException('thre are alread vication for this doctor');
        }
        return vication::where('id', $vicationId)
            ->whereRelation('doctor.clinics', 'clinics.id', Auth::user()->clinic_id)
            ->where('doctor_id', $data['doctor_id'])
            ->update([
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'reason'     => $data['reason'],
                'status'     => $data['status'],
            ]) > 0;
    }
    public function add($data)
    {
        $hasVication = $this->hasVacation($data['doctor_id']);
        if ($hasVication) {
            throw new HasVicationException('thre are alread vication for this doctor');
        }
        return vication::create([
            'clinic_id',
            Auth::user()->clinic_id,
            'doctor_id' => $data['doctor_id'],
            'start_date' => $data['start_date'],
            'end_date'   => $data['end_date'],
            'reason'     => $data['reason'],
            'status'     => $data['status'],
        ]);
    }
    public function delete(int $vicationId): bool
    {
        return Vication::whereKey($vicationId)
            ->whereRelation('doctor.clinics', 'clinics.id', Auth::user()->clinic_id)
            ->firstOrFail()
            ->delete();
    }
    public function hasVacation($doctor_id, $ignore_id = null): bool
    {
        return Vication::where('doctor_id', $doctor_id)
            ->whereIn('status', ['active', 'upcoming'])
            ->whereRelation('doctor.clinics', 'clinics.id', Auth::user()->clinic_id)
            ->when($ignore_id, function ($query) use ($ignore_id) {
                $query->where('id', '!=', $ignore_id);
            })
            ->exists();
    }
    public function getStatistics()
    {
        $stats = vication::whereRelation('doctor.clinics', 'clinics.id', Auth::user()->clinic_id)
            ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 'upcoming' THEN 1 ELSE 0 END) as upcoming,
        SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active,
        SUM(CASE WHEN status = 'ended' THEN 1 ELSE 0 END) as ended
    ")->first();
        return $stats;
    }
}
