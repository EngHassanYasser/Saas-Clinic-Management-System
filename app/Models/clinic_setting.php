<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class clinic_setting extends Model
{
    public function clinic() {
        return $this->belongsTo(clinic::class);
    }
}
