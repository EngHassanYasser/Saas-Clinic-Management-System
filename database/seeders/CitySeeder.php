<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Country;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $kuwait = Country::where('code', 'KW')->first();
        $egypt = Country::where('code', 'EG')->first();
        $saudi = Country::where('code', 'SA')->first();

        City::insert([
            // Kuwait
            [
                'country_id' => $kuwait->id,
                'name' => 'Kuwait City',
                'timezone' => 'Asia/Kuwait',
            ],
            [
                'country_id' => $kuwait->id,
                'name' => 'Hawalli',
                'timezone' => 'Asia/Kuwait',
            ],
            [
                'country_id' => $kuwait->id,
                'name' => 'Al Ahmadi',
                'timezone' => 'Asia/Kuwait',
            ],

            // Egypt
            [
                'country_id' => $egypt->id,
                'name' => 'Cairo',
                'timezone' => 'Africa/Cairo',
            ],
            [
                'country_id' => $egypt->id,
                'name' => 'Alexandria',
                'timezone' => 'Africa/Cairo',
            ],

            // Saudi Arabia
            [
                'country_id' => $saudi->id,
                'name' => 'Riyadh',
                'timezone' => 'Asia/Riyadh',
            ],
            [
                'country_id' => $saudi->id,
                'name' => 'Jeddah',
                'timezone' => 'Asia/Riyadh',
            ],
        ]);
    }
}