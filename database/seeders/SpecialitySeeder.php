<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SpecialitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('specialities')->insert([
            ['name' => 'Cardiology', 'icon_name' => 'heart'],
            ['name' => 'Dermatology', 'icon_name' => 'skin'],
            ['name' => 'Neurology', 'icon_name' => 'brain'],
            ['name' => 'Orthopedics', 'icon_name' => 'bone'],
            ['name' => 'Pediatrics', 'icon_name' => 'child'],
            ['name' => 'Obstetrics and Gynecology', 'icon_name' => 'female'],
            ['name' => 'Ophthalmology', 'icon_name' => 'eye'],
            ['name' => 'Otolaryngology', 'icon_name' => 'ear'],
            ['name' => 'Psychiatry', 'icon_name' => 'head-side-brain'],
            ['name' => 'Urology', 'icon_name' => 'kidneys'],
            ['name' => 'General Surgery', 'icon_name' => 'scalpel'],
            ['name' => 'Plastic Surgery', 'icon_name' => 'face-smile'],
            ['name' => 'Oncology', 'icon_name' => 'ribbon'],
            ['name' => 'Endocrinology', 'icon_name' => 'dna'],
            ['name' => 'Gastroenterology', 'icon_name' => 'stomach'],
            ['name' => 'Pulmonology', 'icon_name' => 'lungs'],
            ['name' => 'Nephrology', 'icon_name' => 'kidneys'],
            ['name' => 'Rheumatology', 'icon_name' => 'hand-dots'],
            ['name' => 'Hematology', 'icon_name' => 'droplet'],
            ['name' => 'Emergency Medicine', 'icon_name' => 'truck-medical'],
        ]);
    }
}
