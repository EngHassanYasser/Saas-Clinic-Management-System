<?php

namespace App\services;

use App\Models\appointment;
use App\Models\doctor_service_price;

class AppointmentService
{

    public function getClinicAppointments($clinic_id)
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
        )->where('clinic_id', $clinic_id)
            ->with(['patient:id,name', 'doctor:id,name', 'clinic:id,name,address', 'service:id,name'])
            ->paginate(20)
            ->through(function ($appt) use ($clinic_id) {
                $price = doctor_service_price::where('doctor_id', $appt->doctor_id)
                    ->where('clinic_id', $clinic_id)
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
