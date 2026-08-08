<?php

namespace App\Services\Speciality;

use App\Models\speciality;
use Illuminate\Database\Eloquent\Collection;

class specialityQueryService
{
    public function getAll(): Collection
    {

        return speciality::select(['id', 'name'])->get();
    }
}
