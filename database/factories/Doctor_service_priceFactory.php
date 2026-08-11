<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Clinic_doctor_medical_service;
use App\Services\MedicalService;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<Medical_Service>
 */
class Clinic_doctor_medical_serviceFactory extends Factory
{
    protected $model = Clinic_doctor_medical_service::class;

    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),

            'doctor_id' => Doctor::factory(),

            'medicalService_id' => Medical_Service::factory(),

            'description' => fake()->sentence(),

            'price' => fake()->randomFloat(2, 50, 2000),
        ];
    }
}