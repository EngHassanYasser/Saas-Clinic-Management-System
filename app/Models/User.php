<?php

namespace App\Models;

use App\Enums\EnRoleType;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'email',
    'password',
    'type',
    'user_name',
    'clinic_id',
    'city_id',
    'google_id',
])]
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
            'type' =>EnRoleType::class,
        ];
    }

    public function usesDashboardLayout(): bool
    {
        return in_array($this->type, [
           EnRoleType::CLINIC->value,
           EnRoleType::PATIENT->value,
        ], true);
    }

    public static function generateUniqueUsername(string $name): string
    {
        $baseUsername = Str::slug($name, '');

        if ($baseUsername === '') {
            $baseUsername = 'user';
        }

        do {
            $username = $baseUsername.random_int(1000, 9999);
        } while (self::where('user_name', $username)->exists());

        return $username;
    }

    public function notification_logs()
    {
        return $this->hasMany(NotificationLog::class);
    }

    public function schedules()
    {
        return $this->hasMany(schedule::class);
    }

    public function appointments()
    {
        return $this->hasMany(appointment::class);
    }
    public function vacations()
    {
        return $this->hasMany(vacation::class);
    }

    public function notifications()
    {
        return $this->hasMany(notification::class);
    }

    public function otps()
    {
        return $this->hasMany(otp::class);
    }

    public function complaints()
    {
        return $this->hasMany(complaint::class);
    }

    public function city()
    {
        return $this->belongsTo(city::class);
    }
}
