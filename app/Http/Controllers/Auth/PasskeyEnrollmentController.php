<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Landing point for the signed, single-purpose enrollment link every
 * newly-created user is sent instead of a password-reset link. The
 * `signed` route middleware already rejects an invalid/expired/tampered
 * link before this runs. Possessing the link is treated as equivalent
 * proof of identity to a password, so it logs the user in directly and
 * marks the session password-confirmed — the same session flag Fortify's
 * passkey registration routes already require, letting the existing,
 * unmodified passkey self-service flow (PasskeyRegister.vue + Fortify's
 * own passkey.registration-options/passkey.store routes) work immediately.
 */
class PasskeyEnrollmentController extends Controller
{
    public function show(Request $request, User $user): Response
    {
        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->passwordConfirmed();

        return Inertia::render('auth/EnrollPasskey');
    }
}
