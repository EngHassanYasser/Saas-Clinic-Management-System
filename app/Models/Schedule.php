<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

#[Fillable([
    'start_time',
    'end_time',
    'slot_duration',
    'start_break',
    'end_break',
    'is_available',
    'clinic_id',
    'doctor_id'
])]
class Schedule extends Model
{

    public function formatTime12Hours($time): string
    {
        return Carbon::createFromFormat('H:i:s', $time)
            ->format('h:i A');
    }
    public function doctor()
    {
        return $this->belongsTo(user::class);
    }
    public function days()
    {
        return $this->belongsToMany(
            Day::class,
            'day_schedule',
            'schedule_id',
            'day_id'
        );
    }
    public function clinic()
    {
        return $this->belongsTo(clinic::class);
    }
}
