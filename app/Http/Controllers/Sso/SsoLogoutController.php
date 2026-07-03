<?php

namespace App\Http\Controllers\Sso;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SsoLogoutController extends Controller
{
    /**
     * Every portal's own logout redirects here after destroying its local
     * session, so logging out anywhere ends the whole SSO session — not
     * just the local one. Without this, Identity's session stays alive and
     * the next visit to any portal (even the same one you just "logged
     * out" of) silently re-authenticates you via /sso/authorize, since
     * that route issues a token immediately for anyone still logged in
     * here. That looked like "logout doesn't work" and is the whole
     * reason this route exists.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
