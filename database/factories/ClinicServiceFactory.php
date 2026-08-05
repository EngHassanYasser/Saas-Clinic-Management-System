<?php

namespace Database\Factories;

use App\Models\ClinicService;
use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ClinicService>
 */
class ClinicServiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->words(2, true),

            'speciality_id' => Speciality::factory(),
        ];
    }
}