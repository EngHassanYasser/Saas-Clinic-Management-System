<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\DoctorService;
use App\Models\Doctor;
use App\Models\Doctor_service_price;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<DoctorServicePrice>
 */
class Doctor_service_priceFactory extends Factory
{
    protected $model = Doctor_service_price::class;

    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),

            'doctor_id' => Doctor::factory(),

            'doctorService_id' => DoctorService::factory(),

            'description' => fake()->sentence(),

            'price' => fake()->randomFloat(2, 50, 2000),
        ];
    }
}