<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'type', 'user_name','speciality_id','clinic_id'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function notification_logs()
    {
        return $this->hasMany(notification_log::class);
    }
    public function schedules()
    {
        return $this->hasMany(schedule::class);
    }
    public function appointments()
    {
        return $this->hasMany(appointment::class);
    }
    public function clinic()
    {
        return $this->hasMany(clinic::class);
    }
    public function clinics()
    {
        return $this->belongsToMany(Clinic::class, 'clinic_users', 'user_id', 'clinic_id');
    }
    public function vications() {
        return $this->hasMany(vication::class);
    }
    public function notifications(){
        return $this->hasMany(notification::class);
    }
    public function otps(){
        return $this->hasMany(otp::class);
    }
}
