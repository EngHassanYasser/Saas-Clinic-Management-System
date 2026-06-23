<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class usersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['name' => 'patient', 'user_name' => 'patient', 'speciality_id' => null, 'type' => 'patient','gendor'=>'male', 'password' => '$2y$12$B5ZdY6ILxh6CGgWiApcVpOp98/yy/7LIlEilU/PJh8bLOQRYv0Lq2','email'=>'patient@gmail.com'],
            ['name' => 'super_admin', 'user_name' => 'super_ admin', 'speciality_id' => null, 'type' => 'super_admin','gendor'=>'male', 'password' => '$2y$12$B5ZdY6ILxh6CGgWiApcVpOp98/yy/7LIlEilU/PJh8bLOQRYv0Lq2','email'=>'super_admin@gmail.com'],
        ];
        DB::table('users')->insert($users);
    }
}
