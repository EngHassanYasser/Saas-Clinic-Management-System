<?php

namespace App\services;

use App\Enums\AppointmentStatus;
use App\Enums\RoleType;
use App\Exceptions\SlotDoesNotAvailable;
use App\Exceptions\UnauthorizedException;
use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor_service_price;
use App\Models\Schedule;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AppointmentService
{
    public function getStats(User $user): Appointment
    {
        return match ($user->type) {
            RoleType::PATIENT->value => $this->getPatientStats($user->id),
            RoleType::CLINIC->value => $this->getClinicStats(Clinic::where('owner_id', $user->id)->value('id')),
            default => new Appointment(),
        };
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

    public function getPatientStats(int $id): Appointment
    {
        return $this->getAppointmentsStatisticsBy('patient_id', $id);
    }
    public function getClinicStats(int $clinicId): Appointment
    {
        return $this->getAppointmentsStatisticsBy('clinic_id', $clinicId);
    }
    public function getAppointments(User $user): LengthAwarePaginator
    {
        if ($user->type == RoleType::PATIENT->value) {
            return $this->getAppointmentsBy('patient_id', $user->id);
        } else if ($user->type == RoleType::CLINIC->value) {
            $clinicId = Clinic::where('owner_id', $user->id)->value('id');
            return $this->getAppointmentsBy('clinic_id', $clinicId);
        }
        return new LengthAwarePaginator(
            new Collection(),
            0,
            15,
            request()->input('page', 1)
        );
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
                    ->where('clinic_service_id', $appt->clinic_service_id)   // مباشرة من العمود
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
    public function reschdule(array $data): bool
    {
        $appointment = Appointment::where('clinic_id', Auth::user()->clinic_id)
            ->findOrFail($data['appointmentId']);
        $slot_duration = $this->getSlotDurationByVisitDate($appointment->clinic_id, $appointment->doctor_id, $data['visit_date']);

        if (is_null($slot_duration)) {
            throw new \Exception('No schedule found.');
        }
        return $appointment->update([
            'visit_date' => $data['visit_date'],
            'start_time' => $data['start_time'],
            'end_time' => Carbon::parse($data['start_time'])->addMinutes($slot_duration),
        ]);
    }
    public function changeStatus(AppointmentStatus $status, int $appointmentId): bool
    {
        $user = Auth::user();

        $appointment = $this->findAppointment($appointmentId, $user);

        $this->authorizeStatusChange($appointment, $status, $user);

        return $appointment->update([
            'status' => $status,
        ]);
    }
    public function getAvailableAppointments(int $clinicId, int $doctorId, string $visitDate): array
    {
        if (Carbon::parse($visitDate)->isBefore(today())) {
            return   [];
        }
        $bookedSlots = $this->getBookedSlots($clinicId, $doctorId, $visitDate);
        $schedules = $this->getSchedules($visitDate, $doctorId, $clinicId);

        return $this->getAvailableSlots($bookedSlots, $schedules);
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
    public function getBookedSlots(int $clinicId, int $doctorId, string $visitDate): array
    {
        return   Appointment::where('clinic_id', $clinicId)
            ->where('doctor_id', $doctorId)
            ->whereDate('visit_date', $visitDate)
            ->get(['start_time', 'visit_date'])
            ->map(fn($appointment) => Carbon::parse($appointment->start_time)->format('H:i'))
            ->toArray();
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

                // لو الـ Slot هيعدي نهاية الدوام
                if ($slotEnd->gt($end)) {
                    break;
                }

                // تخطي وقت الـ Break
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
    private function canPatientChangeStatus(
        AppointmentStatus $currentStatus,
        AppointmentStatus $newStatus
    ): bool {
        return match ($currentStatus) {
            AppointmentStatus::PENDING =>
            $newStatus === AppointmentStatus::CANCELLED,

            AppointmentStatus::CONFIRMED =>
            $newStatus === AppointmentStatus::CANCELLED,

            default => false,
        };
    }
    private function canClinicChangeStatus(
        AppointmentStatus $currentStatus,
        AppointmentStatus $newStatus
    ): bool {
        return match ($currentStatus) {
            AppointmentStatus::PENDING =>
            in_array($newStatus, [
                AppointmentStatus::CONFIRMED,
                AppointmentStatus::CANCELLED,
            ]),

            AppointmentStatus::CONFIRMED =>
            in_array($newStatus, [
                AppointmentStatus::IN_PROGRESS,
                AppointmentStatus::CANCELLED,
                AppointmentStatus::NO_SHOW,
                AppointmentStatus::RESCHEDULED,
            ]),

            AppointmentStatus::IN_PROGRESS =>
            $newStatus === AppointmentStatus::COMPLETED,

            default => false,
        };
    }
    private function findAppointment(int $appointmentId, User $user): Appointment
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
    private function authorizeStatusChange(
        Appointment $appointment,
        AppointmentStatus $status,
        User $user
    ): void {
        if (
            $user->type === RoleType::PATIENT->value &&
            ! $this->canPatientChangeStatus($appointment->status, $status)
        ) {
            throw new UnauthorizedException();
        }

        if (
            $user->type === RoleType::CLINIC->value &&
            ! $this->canClinicChangeStatus($appointment->status, $status)
        ) {
            throw new UnauthorizedException();
        }
    }
    public function confirmAfterPayment(Appointment $appointment): void
    {
        $appointment->update([
            'status' => AppointmentStatus::CONFIRMED,
        ]);
    }
    public function add(array $data): appointment
    {
        return DB::transaction(function () use ($data) {

            $isSlotAvailable = $this->isSlotAvailable($data['clinic_id'], $data['doctor_id'], $data['visit_date'], $data['slot']);
            if (!$isSlotAvailable) {
                throw new SlotDoesNotAvailable();
            }
            $slot_duration = $this->getSlotDurationByVisitDate($data['clinic_id'], $data['doctor_id'], $data['visit_date']);
            return  appointment::create([
                'patient_id'=>Auth::id(),
                'clinic_id'=>$data['clinic_id'],
                'doctor_id'=>$data['doctor_id'],
                'clinic_service_id'=>$data['clinicService_id'],
                'visit_date' => $data['visit_date'],
                'start_time' => $data['slot'],
                'end_time' => Carbon::parse($data['slot'])->addMinutes($slot_duration),
            ]);
        });
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
