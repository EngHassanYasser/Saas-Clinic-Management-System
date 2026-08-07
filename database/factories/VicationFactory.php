<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Vication;
use Illuminate\Database\Eloquent\Factories\Factory;

class VicationFactory extends Factory
{
    protected $model = Vication::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+30 days');

        return [
            'clinic_id' => Clinic::factory(),

            'doctor_id' => Doctor::factory(),

            'start_date' => $startDate->format('Y-m-d'),

            'end_date' => fake()
                ->dateTimeBetween(
                    $startDate,
                    '+60 days'
                )
                ->format('Y-m-d'),

            'reason' => fake()->optional()->sentence(),

            'status' => fake()->randomElement([
                'upcoming',
                'approved',
                'active',
                'ended',
            ]),
        ];
    }
}