<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SSO signing keys
    |--------------------------------------------------------------------------
    |
    | RS256 keypair used to sign the short-lived handoff token issued after
    | a successful login. Only this app ever touches the private key; every
    | consuming app verifies signatures with the public key alone.
    |
    */

    'private_key_path' => storage_path('keys/private.pem'),

    'public_key_path' => storage_path('keys/public.pem'),

    /*
    |--------------------------------------------------------------------------
    | Token lifetime
    |--------------------------------------------------------------------------
    |
    | The JWT is a one-shot redirect handoff, not a session token, so it only
    | needs to survive the single browser redirect to the target app.
    |
    */

    'token_ttl_seconds' => 120,

];
