<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code'])]
class country extends Model
{
    public function cities(){
        return $this->hasMany(city::class);
    }
}
