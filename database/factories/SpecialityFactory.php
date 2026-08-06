<?php

namespace Database\Factories;

use App\Models\Speciality;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Speciality>
 */
class SpecialityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => 'Speciality '.fake()->unique()->numberBetween(1, 100000),

            'icon_name' => fake()->optional()->randomElement([
                'fa-heart',
                'fa-tooth',
                'fa-brain',
                'fa-eye',
                'fa-ear-listen',
                'fa-bone',
                'fa-stethoscope',
                'fa-user-doctor',
            ]),
        ];
    }
}