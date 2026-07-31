<?php

namespace App\Services;

use App\Models\speciality;
use Illuminate\Database\Eloquent\Collection;

class SpecialityService
{
    public function getAll(): Collection
    {
        return speciality::select(['id', 'name'])->get();
    }
    public function getAvailableSpecailities(): Collection {
        return new Collection();
    }
}
