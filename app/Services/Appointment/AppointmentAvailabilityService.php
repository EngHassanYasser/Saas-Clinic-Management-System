<?php

namespace App\Services\Appointment;

use App\DTOs\Services\Appointment\IsSlotAvailableDTO;
use App\DTOs\Services\Appointment\GetSlotDurationByVisitDateDTO;
use App\DTOs\Services\Appointment\GetAvailableAppointmentsDTO;
use App\DTOs\Services\Appointment\GetBookedSlotsDTO;
use App\DTOs\Services\Appointment\GetSchedulesDTO;
use App\Models\Appointment;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AppointmentAvailabilityService
{
    public function getAvailableAppointments(GetAvailableAppointmentsDTO $dto): array
    {
        if (Carbon::parse($dto->visisteDate)->isBefore(today())) {
            return [];
        }
        $getBookedSlotsDTO = new GetBookedSlotsDTO($dto->clinic, $dto->doctor, $dto->visisteDate);
        $getSchedulesDTO = new GetSchedulesDTO($dto->clinic, $dto->doctor, $dto->visisteDate);
        $bookedSlots = $this->getBookedSlots($getBookedSlotsDTO);
        $schedules = $this->getSchedules($getSchedulesDTO);

        return $this->getAvailableSlots($bookedSlots, $schedules);
    }

    public function getAvailableSlots(array $bookedSlots, Collection $schedules): array
    {
        $availableSlots = [];
        foreach ($schedules as $schedule) {

            $current = Carbon::parse($schedule->start_time);
            $end = Carbon::parse($schedule->end_time);
            $duration = (int) $schedule->slot_duration->value;

            while ($current->lt($end)) {

                $slotEnd = $current->copy()->addMinutes($duration);

                if ($slotEnd->gt($end)) {
                    break;
                }

                if (
                    $schedule->start_break &&
                    $schedule->end_break &&
                    $current->lt(Carbon::parse($schedule->end_break)) &&
                    $slotEnd->gt(Carbon::parse($schedule->start_break))
                ) {
                    $current->addMinutes($duration);

                    continue;
                }

                $slot = $current->format('H:i');

                if (! in_array($slot, $bookedSlots)) {
                    $availableSlots[] = $slot;
                }

                $current->addMinutes($duration);
            }
        }

        return $availableSlots;
    }

    public function getBookedSlots(GetBookedSlotsDTO $dto): array
    {
        return Appointment::where('clinic_id', $dto->clinic->id)
            ->where('doctor_id', $dto->doctor->id)
            ->whereDate('visit_date', $dto->visisteDate)
            ->get(['start_time', 'visit_date'])
            ->map(fn ($appointment) => Carbon::parse($appointment->start_time)->format('H:i'))
            ->toArray();
    }

    public function getSchedules(GetSchedulesDTO $dto)
    {
        $dayName = Carbon::parse($dto->visisteDate)->dayName;

        return Schedule::where('clinic_id', $dto->clinic->id)
            ->where('doctor_id', $dto->doctor->id)
            ->where('is_available', 1)
            ->whereHas('days', function ($query) use ($dayName) {
                $query->where('name', $dayName);
            })->get([
                'start_time',
                'end_time',
                'start_break',
                'end_break',
                'slot_duration',
            ]);
    }

    public function getSlotDurationByVisitDate(GetSlotDurationByVisitDateDTO $dto): int
    {
        $dayName = Carbon::parse($dto->visiteDate)->dayName;
        $slot_duration = Schedule::where('clinic_id', $dto->clinicId)
            ->where('doctor_id', $dto->doctorId)
            ->where('is_available', 1)
            ->whereHas('days', function ($query) use ($dayName) {
                $query->where('name', $dayName);
            })->value('slot_duration');

        return (int) ($slot_duration?->value ?? 0);
    }

    public function isSlotAvailable(IsSlotAvailableDTO $dto): bool
    {
        return Appointment::where('clinic_id', $dto->clinicId)
            ->where('doctor_id', $dto->doctorId)
            ->where('visit_date', $dto->visiteDate)
            ->where('start_time', $dto->slot)
            ->doesntExist();
    }
}
