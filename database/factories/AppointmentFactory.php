<?php

namespace Database\Factories;

use App\Enums\EnAppointmentStatus;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Services\MedicalService;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class AppointmentFactory extends Factory
{
    public function definition(): array
    {
        $hour = fake()->numberBetween(8, 16);

        $start = sprintf('%02d:00:00', $hour);
        $end = sprintf('%02d:30:00', $hour);

        return [
            'start_time' => $start,
            'end_time' => $end,

            'status' => EnAppointmentStatus::PENDING,

            'notes' => fake()->optional()->sentence(),

            'cancellation_reason' => null,
            'cancellation_time' => null,

            'deposit_amount' => fake()->randomFloat(2, 0, 500),

            'reminder_sent_at' => null,

            'patient_id' => User::factory(),

            'clinic_id' => Clinic::factory(),

            'doctor_id' => Doctor::factory(),

            'medicalService_id' => Medical_Service::factory(),

            'visit_date' => fake()->date('Y-m-d'),
        ];
    }

    public function cancelled(): static
    {
        return $this->state(function (array $attributes) {

            return [
                'status' => EnAppointmentStatus::CANCELLED,
                'cancellation_reason' => fake()->sentence(),
                'cancellation_time' => Carbon::parse(
                    $attributes['visit_date'].' '.$attributes['start_time']
                )->addMinutes(10),
            ];
        });
    }

    public function confirmed(): static
    {
        return $this->state([
            'status' => EnAppointmentStatus::CONFIRMED,
        ]);
    }

    public function completed(): static
    {
        return $this->state([
            'status' => EnAppointmentStatus::COMPLETED,
        ]);
    }

    public function pending(): static
    {
        return $this->state([
            'status' => EnAppointmentStatus::PENDING,
        ]);
    }
}
