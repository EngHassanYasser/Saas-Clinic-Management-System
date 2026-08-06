<?php

namespace App\Services\Appointment;

use App\Enums\RoleType;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\User;

class AppointmentStatisticsService
{
    public function getStats(User $user): Appointment
    {
        return match ($user->type) {
            RoleType::PATIENT => $this->getPatientStats($user->id),
            RoleType::CLINIC => $this->getClinicStats(Clinic::where('owner_id', $user->id)->value('id')),
            default => new Appointment,
        };
    }

    public function getPatientStats(int $patientId): Appointment
    {
        return $this->getAppointmentsStatisticsBy('patient_id', $patientId);
    }

    public function getClinicStats(int $clinicId): Appointment
    {
        return $this->getAppointmentsStatisticsBy('clinic_id', $clinicId);
    }

    public function getAppointmentsStatisticsBy(string $column, int $id): Appointment
    {
        $statistics = Appointment::where($column, $id)
            ->selectRaw("
        COUNT(*) as total,
        COALESCE(SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END),0) as pending,
        COALESCE(SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END),0) as confirmed,
        COALESCE(SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END),0) as completed,
        COALESCE(SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END),0) as cancelled
    ")->first();

        foreach (['total', 'pending', 'confirmed', 'completed', 'cancelled'] as $field) {
            $statistics->{$field} = (int) $statistics->{$field};
        }

        return $statistics;
    }
}
