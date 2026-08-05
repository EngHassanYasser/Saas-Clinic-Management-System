<?php

namespace App\Models;

use App\Enums\ScheduleSlotDuration;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'start_time',
    'end_time',
    'slot_duration',
    'start_break',
    'end_break',
    'is_available',
    'clinic_id',
    'doctor_id',
])]
class Schedule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'slot_duration' => ScheduleSlotDuration::class,
    ];

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
