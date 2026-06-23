<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'verified_at',
    'expired_at',
    'is_used',
    'attemps',
    'last_sent_at',
    'created_at',
    'updated_at'
])]
class otp extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
