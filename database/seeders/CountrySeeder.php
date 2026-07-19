<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        Country::insert([
            [
                'name' => 'Kuwait',
                'code' => 'KW',
            ],
            [
                'name' => 'Egypt',
                'code' => 'EG',
            ],
            [
                'name' => 'Saudi Arabia',
                'code' => 'SA',
            ],
        ]);
    }
}