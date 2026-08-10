<?php

namespace Database\Factories;

use App\Enums\EnRoleType;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function clinic(): static
    {
        return $this->state(fn () => [
            'type' =>EnRoleType::CLINIC,
        ]);
    }

    public function patient(): static
    {
        return $this->state(fn () => [
            'type' =>EnRoleType::PATIENT,
        ]);
    }

    public function superAdmin(): static
    {
        return $this->state(fn () => [
            'type' =>EnRoleType::SUPER_ADMIN,
        ]);
    }

    public function doctor(): static
    {
        return $this->state(fn () => [
            'type' => 'doctor',
        ]);
    }

    public function definition(): array
    {

        return [
            'name' => fake()->name(),
            'user_name' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),

        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
