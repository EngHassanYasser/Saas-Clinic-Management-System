<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;

#[Fillable([
     'name',
     'email',
     'phone',
     'image'
])]
class Doctor extends Model implements HasMedia
{
     use InteractsWithMedia,HasFactory;
     public function getAvatarUrlAttribute()
     {
          return $this->getFirstMediaUrl('avatar')
               ?: asset('storage/default_profile_image.jpg');
     }
     public function specialities()
     {
          return $this->belongsToMany(Speciality::class);
     }
     public function servicePrices()
     {
          return $this->hasMany(Clinic_doctor_medicalService::class);
     }
     public function clinics()
     {
          return $this->belongsToMany(Clinic::class)->withPivot('is_active');
     }
     public function schedules()
     {
          return $this->hasMany(schedule::class);
     }
     public function appointments() {
          return $this->hasMany(appointment::class);
     }
     public function complaintts() {
          return $this->hasMany(complaint::class);
     }
}
