<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'amount',
    'currency',
    'payment_method',
    'transaction_id',
    'status',
    'type',
    'gatway_response',
    'failture_reason',
    'parent_payment_id',
    'appointment_id'
])]
class Payment extends Model
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
