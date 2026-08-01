<?php

namespace App\Services\Appointment;

use App\Enums\RoleType;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor_service_price;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class AppointmentQueryService
{
    public function getAppointments(User $user): LengthAwarePaginator
    {
        if ($user->type == RoleType::PATIENT->value) {
            return $this->getAppointmentsBy('patient_id', $user->id);
        } else if ($user->type == RoleType::CLINIC->value) {
            $clinicId = Clinic::where('owner_id', $user->id)->value('id');
            return $this->getAppointmentsBy('clinic_id', $clinicId);
        }
        return new LengthAwarePaginator(new Collection(), 0, 15, request()->input('page', 1));
    }

    public function getAppointmentsBy(string $column, int $id): LengthAwarePaginator
    {
        return Appointment::select(
            'id',
            'doctor_id',
            'clinic_id',
            'start_time',
            'end_time',
            'status',
            'cancellation_reason',
            'deposit_amount',
            'cancellation_time',
            'patient_id',
            'clinic_service_id',
            'visit_date',
        )->where($column, $id)
            ->with(['patient:id,name', 'doctor:id,name', 'clinic:id,name,address', 'service:id,name'])
            ->paginate(20)
            ->through(function ($appt) {
                $price = Doctor_service_price::where('doctor_id', $appt->doctor_id)
                    ->where('clinic_id', $appt->clinic_id)
                    ->where('clinic_service_id', $appt->clinic_service_id)
                    ->value('price');
                return [
                    'id'                  => $appt->id,
                    'start_time'          => $appt->start_time,
                    'end_time'            => $appt->end_time,
                    'status'              => $appt->status,
                    'appointment_type'    => $appt->appointment_type,
                    'cancellation_reason' => $appt->cancellation_reason,
                    'deposit_amount'      => $appt->deposit_amount,
                    'cancellation_time'   => $appt->cancellation_time,
                    'visit_date' => $appt->visit_date,

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
    public function findAppointment(int $appointmentId, User $user): Appointment
    {
        $query = Appointment::whereKey($appointmentId);

        if ($user->type === RoleType::CLINIC->value) {
            $clinicId = Clinic::where('owner_id', $user->id)->value('id');

            $query->where('clinic_id', $clinicId);
        }

        if ($user->type === RoleType::PATIENT->value) {
            $query->where('patient_id', $user->id);
        }

        return $query->firstOrFail();
    }
}
