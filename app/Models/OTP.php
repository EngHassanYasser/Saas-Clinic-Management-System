<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'verified_at',
    'expired_at',
    'is_used',
    'attemps',
    'last_sent_at',
])]
class OTP extends Model
{
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
