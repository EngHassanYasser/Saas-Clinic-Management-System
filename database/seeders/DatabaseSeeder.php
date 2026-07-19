<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
        //  SpecialitySeeder::class,
        //  daysSeeder::class,
        //  usersSeeder::class,
        // //  DoctorSeeder::class,
        //  ClinicServicesSeeder::class,
        // CountrySeeder::class,
        // CitySeeder::class,
    ]);
    }
}
