<?php

namespace Database\Factories;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Doctor>
 */
class DoctorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->name(),

            'phone' => fake()->unique()->numerify('01#########'),

            'email' => fake()->unique()->safeEmail(),
        ];
    }
}
