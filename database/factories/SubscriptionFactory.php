<?php

namespace Database\Factories;

use App\Enums\SubscriptionStatus;
use App\Models\Clinic;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubscriptionFactory extends Factory
{
    protected $model = Subscription::class;

    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-3 months', 'now');
        $endDate = (clone $startDate)->modify('+1 month');

        return [
            'start_at' => $startDate->format('Y-m-d'),
            'end_at' => $endDate->format('Y-m-d'),

            'status' => fake()->randomElement([
                'active',
                'expired',
                'cancelled',
                'pending',
            ]),

            'price' => fake()->randomFloat(2, 0, 5000),

            'auto_renew' => fake()->boolean(),

            'clinic_id' => Clinic::factory(),

           'plan_id' => Plan::factory(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => [
            'status' => 'active',
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn () => [
            'status' => 'expired',
        ]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => [
            'status' => 'cancelled',
        ]);
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => 'pending',
        ]);
    }
}