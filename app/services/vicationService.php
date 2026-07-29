<?php

namespace App\services;

use App\Exceptions\hasVicationException;
use App\Models\vication;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class vicationService
{
    public function getClinicVacations(int $clinicId):LengthAwarePaginator 
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
        return DB::transaction(function () use ($data, $vicationId) {
            $vacation = Vication::lockForUpdate()
                ->whereRelation('doctor.clinics', 'clinics.id', Auth::user()->clinic_id)
                ->findOrFail($vicationId);

            if ($this->hasVacation($data['doctor_id'], $vicationId)) {
                throw new HasVicationException('thre are alread vication for this doctor');
            }

            return $vacation->update([
                'start_date' => $data['start_date'],
                'end_date'   => $data['end_date'],
                'reason'     => $data['reason'],
                'status'     => $data['status'],
            ]) > 0;
        });
    }
    public function add(array $data): vication
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
    public function hasVacation(int $doctor_id,int $ignore_id = 0): bool
    {
        return Vication::where('doctor_id', $doctor_id)
            ->whereIn('status', ['active', 'upcoming'])
            ->whereRelation('doctor.clinics', 'clinics.id', Auth::user()->clinic_id)
            ->when($ignore_id, function ($query) use ($ignore_id) {
                $query->where('id', '!=', $ignore_id);
            })
            ->exists();
    }
    public function getStatistics() :array
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
