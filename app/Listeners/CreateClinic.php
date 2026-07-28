<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\clinic;
class CreateClinic
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserCreated $event): void
    {
        $user = $event->user;

        if ($user->type === 'clinic') {
            $clinic =  Clinic::create([
                'owner_id' => $user->id,
                'name' => $user->name,
                'phone' => '+012456574',
                'email' => $user->email,
                // optional fields من الجدول
                'description' => null,
                'slug' => 'testclinic',
                'address' => 'jdlsfks',
                'latitude' => null,
                'longitude' => null,
                'logo' => null,
                'image_cover_name' => null,

                'is_featured' => 0,
                'featured_until' => null,
            ]);
            $user->update([
                'clinic_id' => $clinic->id
            ]);
        }
    }
}
