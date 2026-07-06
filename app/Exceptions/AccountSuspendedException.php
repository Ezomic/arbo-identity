<?php

namespace App\Exceptions;

use RuntimeException;

class AccountSuspendedException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('This account has been suspended.');
    }
}
