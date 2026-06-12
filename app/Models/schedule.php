<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class schedule extends Model
{
    public function doctor()
    {
        return $this->belongsTo(user::class);
    }
    public function days()
    {
        return $this->hasMany(Day::class);
    }
}
