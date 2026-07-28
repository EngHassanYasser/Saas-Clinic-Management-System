<?php

namespace App\Exceptions;

use Exception;

class ActiveSubscriptionNotFoundException extends Exception
{
    public function __construct(
        string $message = 'Clinic does not have an active subscription.'
    ) {
        parent::__construct($message);
    }
}
