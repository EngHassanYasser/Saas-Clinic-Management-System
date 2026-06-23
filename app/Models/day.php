<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'name'
])]
class day extends Model
{
    public $timestamp = false;
    public function schedules()
    {
        return $this->belongsTo(Schedule::class);
    }
}
