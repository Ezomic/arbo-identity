<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePlatformAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null || $user->user_type_id !== 'platform_admin') {
            abort(403);
        }

        return $next($request);
    }
}
