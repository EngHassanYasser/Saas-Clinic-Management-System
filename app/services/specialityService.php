<?php
namespace App\services;

use App\Models\speciality;

class SpecialityService {
    public function getAll() {
        return speciality::select(['id','name'])->get();
    }
    public function getAvailableSpecailities() {
       
    }
}