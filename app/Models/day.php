<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class day extends Model
{
    public $timestamp = false;
    public function schedules()
    {
        return $this->belongsTo(Schedule::class);
    }
}
