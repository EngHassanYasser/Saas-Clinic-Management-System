<?php

namespace Database\Factories;

use App\Models\Clinic;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;

class ComplainFactory extends Factory
{
    public function definition(): array
    {
        return [
            'clinic_id' => Clinic::factory(),

            'user_id' => User::factory(),

            'doctor_id' => Doctor::factory(),

            'department' => fake()->randomElement([
                'radiology',
                'reception',
                'laboratory',
                'pharmacy',
                'accounting',
                'customer_service',
                'nursing',
                'administration',
                'clinics',
                'technical_support',
            ]),

            'visit_date' => fake()->date(),

            'severity' => fake()->randomElement([
                'low',
                'medium',
                'high',
                'critical',
            ]),

            'issue_type' => fake()->randomElement([
                'complaint',
                'suggestion',
                'technical_issue',
                'billing',
                'medical',
                'other',
            ]),

            'description' => fake()->sentence(10),

            'status' => fake()->randomElement([
                'pending',
                'under_review',
                'resolved',
                'rejected',
            ]),

            'resolution_notes' => fake()->optional()->sentence(8),

            'resolved_at' => fake()->optional()->dateTime(),

            'patient_name' => fake()->name(),
        ];
    }
}