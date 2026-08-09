<?php

namespace App\Services\Appointment;

use App\DTOs\Services\Appointment\AppointmentQueryService\GetSlotDurationByVisitDateDTO;
use App\DTOs\Services\AppointmentService\RescheduleDTO;
use App\DTOs\Services\AppointmentService\StoreAppointmentDTO;
use App\Enums\AppointmentStatus;
use App\Exceptions\SlotDoesNotAvailable;
use App\Models\Appointment;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function __construct(private AppointmentAvailabilityService $appointmentQueryService) {}

    public function add(StoreAppointmentDTO $dto, int $patientId): Appointment
    {
        return DB::transaction(function () use ($dto, $patientId) {

            $isSlotAvailable = $this->appointmentQueryService->isSlotAvailable($data['clinic_id'], $data['doctor_id'], $data['visit_date'], $data['slot']);
            if (! $isSlotAvailable) {
                throw new SlotDoesNotAvailable;
            }
            $slot_duration = $this->appointmentQueryService->getSlotDurationByVisitDate($data['clinic_id'], $data['doctor_id'], $data['visit_date']);

            return Appointment::create([
                'patient_id' => $patientId,
                'clinic_id' => $dto->clinicId,
                'doctor_id' => $dto->doctorId,
                'doctorService_id' => $dto->DoctorServiceId,
                'visit_date' =>$dto->visiteDate,
                'start_time' => $dto->slot,
                'end_time' => Carbon::parse($dto->slot)->addMinutes($slot_duration),
            ]);
        });
    }

    public function reschedule(RescheduleDTO $dto): bool
    {
        $getSlotDurationByVisitDateDTO = new GetSlotDurationByVisitDateDTO($dto->appointment->clinic_id, $dto->appointment->doctor_id, $dto->visiteDate);
        $slot_duration = $this->appointmentQueryService->getSlotDurationByVisitDate($getSlotDurationByVisitDateDTO);
        if ($slot_duration <= 0) {
            throw new Exception('No schedule found.');
        }

        return $dto->appointment->update([
            'visit_date' => $dto->visiteDate,
            'start_time' => $dto->startTime,
            'end_time' => Carbon::parse($dto->startTime)
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
