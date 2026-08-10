<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Clinic_doctor_medicalService;
use App\Models\MedicalService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<MedicalService>
 */
class Clinic_doctor_medicalServiceFactory extends Factory
{
    protected $model = Clinic_doctor_medicalService::class;

    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),

            'doctor_id' => Doctor::factory(),

            'medicalService_id' => MedicalService::factory(),

            'description' => fake()->sentence(),

            'price' => fake()->randomFloat(2, 50, 2000),
        ];
    }
}