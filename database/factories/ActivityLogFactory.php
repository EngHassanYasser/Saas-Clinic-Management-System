<?php

namespace Database\Factories;

use App\Models\Activity_log;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity_log>
 */
class Activity_logFactory extends Factory
{
    protected $model = Activity_log::class;

    public function definition(): array
    {
        return [
            'type' => fake()->randomElement([
                'created',
                'updated',
                'deleted',
                'status_changed',
            ]),

            'title' => fake()->sentence(4),

            'description' => fake()->optional()->paragraph(),

            'status' => fake()->optional()->randomElement([
                'success',
                'failed',
                'pending',
            ]),

            'subject_type' => User::class,
            'subject_id' => User::factory(),

            'created_by' => User::factory(),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}