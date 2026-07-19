<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'slug',
    'owner_id',
    'name',
    'phone',
    'email',
    'description',
    'address',
    'latitude',
    'logitude',
    'logo',
    'city_id',
])]
class clinic extends Model
{
    public function appointments()
    {
        return $this->hasMany(appointment::class);
    }
    public function owner()
    {
        return $this->belongsTo(user::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class, 'clinic_users', 'clinic_id', 'user_id');
    }
    public function settings()
    {
        return $this->hasOne(clinic_setting::class);
    }
    public function banners()
    {
        return $this->hasMany(banner::class);
    }
    public function specialities()
    {
        return $this->belongsToMany(
            Speciality::class,
            'clinic_specialities'
        );
    }
    public function schedules()
    {
        return $this->hasMany(schedule::class);
    }
    public function servicePrices()
    {
        return $this->hasMany(doctor_service_price::class);
    }
    public function clinic_doctor()
    {
        return $this->hasMany(clinic_doctor::class);
    }
    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'clinic_doctors', 'clinic_id', 'doctor_id');
    }
    public function complains()
    {
        return $this->hasMany(complain::class);
    }
    public function city()
    {
        return $this->belongsTo(city::class);
    }
    public function subscriptions() {
        return $this->hasMany(subscription::class);
    }
}
