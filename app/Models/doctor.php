<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class doctor extends Model implements HasMedia
{
     use InteractsWithMedia;
     protected $fillable = ['name', 'email', 'phone','image'];
     public function getAvatarUrlAttribute()
     {
          return $this->getFirstMediaUrl('avatar')
               ?: asset('storage/default_profile_image.jpg');
     }
     public function specialities()
     {
          return $this->belongsToMany(Speciality::class);
     }
}
