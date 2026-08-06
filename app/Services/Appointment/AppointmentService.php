<?php

namespace App\Services\Appointment;

use App\Enums\AppointmentStatus;
use App\Exceptions\SlotDoesNotAvailable;
use App\Models\Appointment;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function __construct(private AppointmentAvailabilityService $appointmentQueryService) {}

    public function add(array $data, int $patientId): Appointment
    {
        return DB::transaction(function () use ($data, $patientId) {

            $isSlotAvailable = $this->appointmentQueryService->isSlotAvailable($data['clinic_id'], $data['doctor_id'], $data['visit_date'], $data['slot']);
            if (! $isSlotAvailable) {
                throw new SlotDoesNotAvailable;
            }
            $slot_duration = $this->appointmentQueryService->getSlotDurationByVisitDate($data['clinic_id'], $data['doctor_id'], $data['visit_date']);

            return Appointment::create([
                'patient_id' => $patientId,
                'clinic_id' => $data['clinic_id'],
                'doctor_id' => $data['doctor_id'],
                'clinic_service_id' => $data['clinic_service_id'],
                'visit_date' => $data['visit_date'],
                'start_time' => $data['slot'],
                'end_time' => Carbon::parse($data['slot'])->addMinutes($slot_duration),
            ]);
        });
    }

    public function reschedule(array $data, int $clinicId): bool
    {
        $appointment = Appointment::where('clinic_id', $clinicId)
            ->findOrFail($data['appointmentId']);
        $slot_duration = $this->appointmentQueryService->getSlotDurationByVisitDate($appointment->clinic_id, $appointment->doctor_id, $data['visit_date']);
        if ($slot_duration <= 0) {
            throw new Exception('No schedule found.');
        }
        return $appointment->update([
            'visit_date' => $data['visit_date'],
            'start_time' => $data['start_time'],
            'end_time' => Carbon::parse($data['start_time'])
                ->addMinutes($slot_duration)
                ->toTimeString(),
        ]);
    }

    public function confirmAfterPayment(Appointment $appointment): void
    {
        $appointment->update([
            'status' => AppointmentStatus::CONFIRMED,
        ]);
    }
}
