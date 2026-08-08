<?php

namespace App\Services\Appointment;

use App\Enums\RoleType;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class AppointmentStatisticsService
{
    public function getStats(User $user): array
    {
        return match ($user->type) {
            RoleType::PATIENT => $this->getPatientStats($user->id),
            RoleType::CLINIC => $this->getClinicStats(Clinic::where('owner_id', $user->id)->value('id')),
            default => [
                'total' => 0,
                'pending' => 0,
                'confirmed' => 0,
                'completed' => 0,
                'cancelled' => 0,
            ],
        };
    }

    public function getPatientStats(int $patientId): array
    {
        return $this->getAppointmentsStatisticsBy('patient_id', $patientId);
    }

    public function getClinicStats(int $clinicId): array
    {
        return $this->getAppointmentsStatisticsBy('clinic_id', $clinicId);
    }

    public function getAppointmentsStatisticsBy(string $column, int $id): array
    {
        $cacheKey = "appointments.statistics.{$column}.{$id}";

        return Cache::remember(
            $cacheKey,
            now()->addMinutes(5),
            function () use ($column, $id) {
                $statistics = Appointment::where($column, $id)
                    ->selectRaw("
                    COUNT(*) as total,
                    COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END), 0) as pending,
                    COALESCE(SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END), 0) as confirmed,
                    COALESCE(SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END), 0) as completed,
                    COALESCE(SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END), 0) as cancelled
                ")
                    ->first();

                return [
                    'total' => (int) $statistics->total,
                    'pending' => (int) $statistics->pending,
                    'confirmed' => (int) $statistics->confirmed,
                    'completed' => (int) $statistics->completed,
                    'cancelled' => (int) $statistics->cancelled,
                ];
            }
        );
    }
}
