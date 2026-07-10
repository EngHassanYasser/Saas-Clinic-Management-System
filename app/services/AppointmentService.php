<?php
namespace App\services;

use App\Models\appointment;
use App\Models\doctor_service_price;

class AppointmentService
{
    public function getStats($user)
    {
        if ($user->type == 'patient') {
            return $this->getPatientStats($user->id);
        } else if ($user->type == 'clinic') {
            return $this->getClinicStats($user->clinic_id);
        } else {
            return [];
        }
    }
    public function getAppointmentsStatisticsBy($column, $id)
    {
        $stats = Appointment::where($column, $id)
            ->selectRaw("
        COUNT(*) as total,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
        SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
        SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled
    ")->first();

        return $stats;
    }

    public function getPatientStats($id)
    {
        return $this->getAppointmentsStatisticsBy('patient_id', $id);
    }
    public function getClinicStats($clinic_id)
    {
        return $this->getAppointmentsStatisticsBy('clinic_id', $clinic_id);
    }
    public function getAppointments($user)
    {
        if ($user->type == 'patient') {
            return $this->getAppointmentsBy('patient_id', $user->id);
        } else if ($user->type == 'clinic') {
            return $this->getAppointmentsBy('clinic_id', $user->clinic_id);
        }
        return collect([]);
    }

    public function getAppointmentsBy(string $column, $id)
    {
        return Appointment::select(
            'id',
            'doctor_id',
            'clinic_id',
            'start_time',
            'end_time',
            'status',
            'appointment_type',
            'cancellation_reason',
            'deposit_amount',
            'cancellation_time',
            'patient_id',
            'clinic_service_id',
        )->where($column, $id)
            ->with(['patient:id,name', 'doctor:id,name', 'clinic:id,name,address', 'service:id,name'])
            ->paginate(20)
            ->through(function ($appt) {
                $price = doctor_service_price::where('doctor_id', $appt->doctor_id)
                    ->where('clinic_id', $appt->clinic_id)
                    ->where('clinic_service_id', $appt->clinic_service_id)   // مباشرة من العمود
                    ->value('price');
                return [
                    'id'                  => $appt->id,
                    'start_time'          => $appt->end_time,
                    'end_time'            => $appt->end_time,
                    'status'              => $appt->status,
                    'appointment_type'    => $appt->appointment_type,
                    'cancellation_reason' => $appt->cancellation_reason,
                    'deposit_amount'      => $appt->deposit_amount,
                    'cancellation_time'   => $appt->cancellation_time,

                    'patient' => [
                        'id'   => $appt->patient?->id,
                        'name' => $appt->patient?->name,
                    ],

                    'doctor' => [
                        'id'   => $appt->doctor?->id,
                        'name' => $appt->doctor?->name,
                    ],

                    'clinic' => [
                        'id'   => $appt->clinic?->id,
                        'name' => $appt->clinic?->name,
                        'address' => $appt->clinic?->address
                    ],
                    'service' => [
                        'id' => $appt->service?->id,
                        'name' => $appt->service?->name,
                        'price' => $price,
                    ]
                ];
            });
    }
}
