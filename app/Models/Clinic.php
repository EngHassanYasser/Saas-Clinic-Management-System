<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

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
    'open_time',
    'close_time',
])]
class Clinic extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('logo')
            ->singleFile();
    }

    public function appointments()
    {
        return $this->hasMany(appointment::class);
    }

    public function owner()
    {
        return $this->belongsTo(user::class);
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
            Speciality::class
        );
    }

    public function schedules()
    {
        return $this->hasMany(schedule::class);
    }

    public function servicePrices()
    {
        return $this->hasMany(Clinic_doctor_medicalService::class);
    }

    public function clinic_doctor()
    {
        return $this->hasMany(clinic_doctor::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class);
    }

    public function complaintts()
    {
        return $this->hasMany(complaint::class);
    }

    public function city()
    {
        return $this->belongsTo(city::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(subscription::class);
    }

    public function latestSubscription()
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    public function days()
    {
        return $this->belongsToMany(Day::class);
    }
}
