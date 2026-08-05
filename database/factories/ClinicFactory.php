<?php

namespace Database\Factories;

use App\Models\City;
use App\Models\Clinic;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Clinic>
 */
class ClinicFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'slug' => fake()->unique()->slug(),
            'description' => fake()->paragraph(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->unique()->safeEmail(),
            'address' => fake()->address(),
            'open_time' => '09:00:00',
            'close_time' => '17:00:00',
            'latitude' => fake()->latitude(),
            'longitude' => fake()->longitude(),
            'logo' => null,
            'image_cover_name' => null,
            'is_featured' => false,
            'featured_until' => null,
            'owner_id' => User::factory(),
            'city_id' => City::factory(),
        ];
    }
}
