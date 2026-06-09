<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class banner extends Model
{
    public function clinic() {
        return $this->belongsTo(clinic::class);
    }
}
