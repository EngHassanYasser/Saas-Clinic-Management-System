<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ScheduleFactory extends Factory
{
 public function definition(): array
{
    return [
        'start_time' => fake()->randomElement([
            '08:00:00',
            '09:00:00',
            '10:00:00',
        ]),

        'end_time' => fake()->randomElement([
            '16:00:00',
            '17:00:00',
            '18:00:00',
        ]),

        'slot_duration' => fake()->randomElement([
            '15',
            '30',
            '45',
            '60',
            '90',
            '120',
        ]),

        'start_break' => null,
        'end_break' => null,

        'is_available' => true,

        'clinic_id' => Clinic::factory(),
        'doctor_id' => Doctor::factory(),
    ];
}
}
