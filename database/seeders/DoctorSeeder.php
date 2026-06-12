<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Doctor;
use Faker\Factory as Faker;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        for ($i = 0; $i < 20; $i++) {
            Doctor::create([
                'name'  => $faker->name(),
                'image' => $faker->imageUrl(200, 200, 'people'), 
                'phone' => $faker->phoneNumber(),
                'email' => $faker->unique()->safeEmail(),
            ]);
        }
    }
}