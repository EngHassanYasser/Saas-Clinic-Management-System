<?php

namespace App\Listeners;

use App\Enums\RoleType;
use App\Events\UserCreated;
use App\Models\Clinic;
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
       
    }
}
