<?php

namespace Database\Factories;

use App\Models\Plan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Plan>
 */
class PlanFactory extends Factory
{
    protected $model = Plan::class;

    public function definition(): array
    {
        return [
            'name' => fake()->randomElement([
                'Basic',
                'Standard',
                'Professional',
                'Enterprise',
            ]).' '.fake()->numberBetween(1, 99999),

            'monthly_price' => fake()->randomFloat(2, 0, 9999),

            'max_doctors' => fake()->numberBetween(1, 50),

            'monthly_appointments_limit' => fake()->numberBetween(10, 1000),

            'features' => [
                'online_booking' => fake()->boolean(),
                'whatsapp_notifications' => fake()->boolean(),
                'reports' => fake()->boolean(),
                'multi_branch' => fake()->boolean(),
            ],

            'status' => fake()->randomElement([
                'active',
                'inactive',
                'archived',
            ]),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => [
            'status' => 'active',
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => [
            'status' => 'inactive',
        ]);
    }

    public function archived(): static
    {
        return $this->state(fn () => [
            'status' => 'archived',
        ]);
    }
}
