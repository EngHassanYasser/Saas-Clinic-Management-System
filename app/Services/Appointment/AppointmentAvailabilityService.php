<?php

namespace App\services;

use App\Models\Appointment;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class AppointmentAvailabilityService
{
    public function getAvailableAppointments(int $clinicId, int $doctorId, string $visitDate): array
    {
        if (Carbon::parse($visitDate)->isBefore(today())) {
            return   [];
        }
        $bookedSlots = $this->getBookedSlots($clinicId, $doctorId, $visitDate);
        $schedules = $this->getSchedules($visitDate, $doctorId, $clinicId);

        return $this->getAvailableSlots($bookedSlots, $schedules);
    }
    public function getAvailableSlots(array $bookedSlots, Collection $schedules): array
    {
        $availableSlots = [];
        foreach ($schedules as $schedule) {

            $current = Carbon::parse($schedule->start_time);
            $end = Carbon::parse($schedule->end_time);
            $duration = (int) $schedule->slot_duration;

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


                if (!in_array($slot, $bookedSlots)) {
                    $availableSlots[] = $slot;
                }

                $current->addMinutes($duration);
            }
        }
        return $availableSlots;
    }
    public function getBookedSlots(int $clinicId, int $doctorId, string $visitDate): array
    {
        return   Appointment::where('clinic_id', $clinicId)
            ->where('doctor_id', $doctorId)
            ->whereDate('visit_date', $visitDate)
            ->get(['start_time', 'visit_date'])
            ->map(fn($appointment) => Carbon::parse($appointment->start_time)->format('H:i'))
            ->toArray();
    }
    public function getSchedules(string $visitDate, int $doctorId, int $clinicId)
    {
        $dayName = Carbon::parse($visitDate)->dayName;
        return  Schedule::where('clinic_id', $clinicId)
            ->where('doctor_id', $doctorId)
            ->where('is_available', 1)
            ->whereHas('days', function ($query) use ($dayName) {
                $query->where('name', $dayName);
            })->get([
                'start_time',
                'end_time',
                'start_break',
                'end_break',
                'slot_duration'
            ]);
    }
    public  function getSlotDurationByVisitDate(int $clinicId, int $doctorId, string $visitDate): int
    {
        $dayName = Carbon::parse($visitDate)->dayName;
        return (int) Schedule::where('clinic_id', $clinicId)
            ->where('doctor_id', $doctorId)
            ->where('is_available', 1)
            ->whereHas('days', function ($query) use ($dayName) {
                $query->where('name', $dayName);
            })->value('slot_duration');
    }
    public function isSlotAvailable(
        int $clinicId,
        int $doctorId,
        string $visitDate,
        string $slot,
    ): bool {
        return Appointment::where('clinic_id', $clinicId)
            ->where('doctor_id', $doctorId)
            ->where('visit_date', $visitDate)
            ->where('start_time', $slot)
            ->doesntExist();
    }
}
