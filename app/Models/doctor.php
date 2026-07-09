<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

#[Fillable([
     'name',
     'email',
     'phone',
     'image'
])]
class doctor extends Model implements HasMedia
{
     use InteractsWithMedia;
     public function getAvatarUrlAttribute()
     {
          return $this->getFirstMediaUrl('avatar')
               ?: asset('storage/default_profile_image.jpg');
     }
     public function specialities()
     {
          return $this->belongsToMany(Speciality::class);
     }
     public function doctor_service_price()
     {
          return $this->hasMany(doctor_service_price::class);
     }
     public function clinics()
     {
          return $this->belongsToMany(Clinic::class, 'clinic_doctors', 'doctor_id', 'clinic_id');
     }
     public function schedules()
     {
          return $this->hasMany(schedule::class);
     }
     public function appointments() {
          return $this->hasMany(appointment::class);
     }
}
