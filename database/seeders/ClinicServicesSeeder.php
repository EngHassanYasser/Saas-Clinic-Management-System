<?php

namespace Database\Seeders;

use App\Models\DoctorService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoctorServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
           $services = [

            // أسنان (speciality_id = 1)
            ['name' => 'كشف', 'speciality_id' => 1],
            ['name' => 'تنظيف جير', 'speciality_id' => 1],
            ['name' => 'حشو عادي', 'speciality_id' => 1],
            ['name' => 'حشو عصب', 'speciality_id' => 1],
            ['name' => 'خلع', 'speciality_id' => 1],
            ['name' => 'تركيب تاج', 'speciality_id' => 1],

            // باطنة (speciality_id = 2)
            ['name' => 'كشف', 'speciality_id' => 2],
            ['name' => 'متابعة', 'speciality_id' => 2],
            ['name' => 'استشارة', 'speciality_id' => 2],

            // أطفال (speciality_id = 3)
            ['name' => 'كشف', 'speciality_id' => 3],
            ['name' => 'متابعة', 'speciality_id' => 3],
            ['name' => 'تطعيم', 'speciality_id' => 3],

            // جلدية (speciality_id = 4)
            ['name' => 'كشف', 'speciality_id' => 4],
            ['name' => 'جلسة ليزر', 'speciality_id' => 4],
            ['name' => 'حقن بلازما', 'speciality_id' => 4],

            // عظام (speciality_id = 5)
            ['name' => 'كشف', 'speciality_id' => 5],
            ['name' => 'متابعة', 'speciality_id' => 5],
            ['name' => 'حقن مفاصل', 'speciality_id' => 5],
        ];

        DoctorService::insert($services);
    }
}
