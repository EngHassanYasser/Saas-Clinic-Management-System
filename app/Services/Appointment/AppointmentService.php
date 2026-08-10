<?php

namespace App\Services\Appointment;

use App\DTOs\Services\Appointment\IsSlotAvailableDTO;
use App\DTOs\Services\Appointment\GetSlotDurationByVisitDateDTO;
use App\DTOs\Services\Appointment\RescheduleDTO;
use App\DTOs\Services\Appointment\StoreAppointmentDTO;
use App\Enums\EnAppointmentStatus;
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

            $isAvailabledto = new IsSlotAvailableDTO($dto->clinicId,
                $dto->doctorId,
                $dto->visiteDate,
                $dto->slot);

            $isSlotAvailable = $this->appointmentQueryService->isSlotAvailable($isAvailabledto);
            if (! $isSlotAvailable) {
                throw new SlotDoesNotAvailable;
            }
            $getSlotDurationByVisitDateDTO = new GetSlotDurationByVisitDateDTO($dto->clinicId,$dto->doctorId,$dto->visiteDate);

            $slot_duration = $this->appointmentQueryService->getSlotDurationByVisitDate($getSlotDurationByVisitDateDTO);

            return Appointment::create([
                'patient_id' => $patientId,
                'clinic_id' => $dto->clinicId,
                'doctor_id' => $dto->doctorId,
                'medicalService_id' => $dto->DoctorServiceId,
                'visit_date' => $dto->visiteDate,
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
            'status' => EnAppointmentStatus::CONFIRMED,
        ]);
    }
}
