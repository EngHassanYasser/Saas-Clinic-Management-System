<?php

namespace App\services;

use App\Models\doctor;

class DoctorService
{
    public function getAll()
    {
        return Doctor::select('name', 'email', 'phone')->get();
    }
    // public function addNewDoctor() {
    //     Doctor::create([

    //     ])
    // }
}
