<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'title',
    'image',
    'redirct_url',
    'sent_at',
    'end_at',
    'is_active',
    'clinic_id'
])]
class banner extends Model
{
    public function clinic()
    {
        return $this->belongsTo(clinic::class);
    }
}
