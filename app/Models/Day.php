<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
    'name'
])]
class Day extends Model
{
    use HasFactory;
    public $timestamps = false;
    public function schedules()
    {
        return $this->belongsToMany(
            Schedule::class,
            'day_schedule',
            'day_id',
            'schedule_id'
        );
    }
    public function clinics()
    {
        return $this->belongsToMany(Clinic::class);
    }
}
