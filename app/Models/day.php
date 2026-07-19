<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name'
])]
class day extends Model
{
    public $timestamp = false;
     public function schedules()
    {
        return $this->belongsToMany(
            Schedule::class,
            'day_schedule',
            'day_id',
            'schedule_id'
        );
    }
}
