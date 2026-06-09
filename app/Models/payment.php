<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    public function appointment() {
        return $this->belongsTo(appointment::class);
    }
}
