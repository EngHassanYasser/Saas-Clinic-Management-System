<?php

namespace App\Exceptions;

use Exception;

class ActiveSubscriptionAlreadyExistsException extends Exception
{
    public function __construct(
        string $message = 'Clinic already have an active subscription.'
    ) {
        parent::__construct($message);
    }
}
