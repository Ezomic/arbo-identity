<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthenticateApiClient
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();
        $expected = config('services.internal_api_token');

        if (! $token || ! $expected || ! hash_equals($expected, $token)) {
            abort(401, 'Unauthorized');
        }

        return $next($request);
    }
}
