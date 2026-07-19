<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
#[Fillable(['name','timezone','country_id'])]
class city extends Model
{
    public function country() {
        return $this->belongsTo(country::class);
    }
    public function clinics(){
        return $this->hasMany(clinic::class);
    }
    public function users() {
        return $this->hasMany(user::class);
    }
}
