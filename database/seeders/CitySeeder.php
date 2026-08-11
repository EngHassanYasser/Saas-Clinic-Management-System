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
                'countryId' => $kuwait->id,
                'name' => 'Kuwait City',
                'timezone' => 'Asia/Kuwait',
            ],
            [
                'countryId' => $kuwait->id,
                'name' => 'Hawalli',
                'timezone' => 'Asia/Kuwait',
            ],
            [
                'countryId' => $kuwait->id,
                'name' => 'Al Ahmadi',
                'timezone' => 'Asia/Kuwait',
            ],

            // Egypt
            [
                'countryId' => $egypt->id,
                'name' => 'Cairo',
                'timezone' => 'Africa/Cairo',
            ],
            [
                'countryId' => $egypt->id,
                'name' => 'Alexandria',
                'timezone' => 'Africa/Cairo',
            ],

            // Saudi Arabia
            [
                'countryId' => $saudi->id,
                'name' => 'Riyadh',
                'timezone' => 'Asia/Riyadh',
            ],
            [
                'countryId' => $saudi->id,
                'name' => 'Jeddah',
                'timezone' => 'Asia/Riyadh',
            ],
        ]);
    }
}