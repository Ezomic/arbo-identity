<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Account lockout
    |--------------------------------------------------------------------------
    |
    | After this many consecutive failed login attempts, the account is
    | locked for the configured duration — on top of (not instead of)
    | Fortify's existing per-username+IP rate limiter.
    |
    */

    'max_failed_login_attempts' => env('MAX_FAILED_LOGIN_ATTEMPTS', 5),

    'lockout_duration_minutes' => env('LOCKOUT_DURATION_MINUTES', 15),

];
