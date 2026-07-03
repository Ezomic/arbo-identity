<?php

namespace App\Exceptions;

use RuntimeException;

class UserHasNoAccessToAppException extends RuntimeException
{
    public function __construct(string $appSlug)
    {
        parent::__construct("User has no role assigned for the \"{$appSlug}\" app.");
    }
}
