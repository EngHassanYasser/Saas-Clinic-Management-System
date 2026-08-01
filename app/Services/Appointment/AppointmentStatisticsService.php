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
            RoleType::PATIENT->value => $this->getPatientStats($user->id),
            RoleType::CLINIC->value => $this->getClinicStats(Clinic::where('owner_id', $user->id)->value('id')),
            default => new Appointment(),
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
        return Appointment::where($column, $id)
            ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled")->first();
    }
}
