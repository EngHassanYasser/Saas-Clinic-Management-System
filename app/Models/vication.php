<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class vication extends Model
{
    public function doctors() {
        return $this->belongsTo(User::class);
    }
}
