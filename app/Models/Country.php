<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['name', 'code'])]
class Country extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function cities()
    {
        return $this->hasMany(city::class);
    }
}
