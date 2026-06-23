<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'id',
    'amount',
    'currency',
    'payment_method',
    'transaction_id',
    'status',
    'type',
    'gatway_response',
    'failture_reason',
    'created_at',
    'updated_at',
    'parent_payment_id',
    'appointment_id'
])]
class payment extends Model
{
    public function parent()
    {
        return $this->belongsTo(Payment::class, 'parent_payment_id');
    }
    public function children()
    {
        return $this->hasMany(Payment::class, 'parent_payment_id');
    }
    public function appointment()
    {
        return $this->belongsTo(appointment::class);
    }
}
