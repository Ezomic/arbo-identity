<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class Require2FA
{
    // If the user's tenant has require_2fa = true and the user has not yet
    // confirmed TOTP two-factor, redirect them to the 2FA setup page. A
    // passkey no longer satisfies this on its own — since ARBO-89 removed
    // password login, a passkey is now every user's mandatory *primary*
    // credential (they can't be authenticated without one), so it no
    // longer signals an extra factor on top of anything. require_2fa now
    // means "TOTP required in addition to your passkey."
    public function handle(Request $request, Closure $next): Response
    {
        /** @var User|null $user */
        $user = Auth::user();

        if ($user === null) {
            return $next($request);
        }

        $tenant = Tenant::query()->find($user->tenant_id);

        if ($tenant?->require_2fa && ! $this->userHas2FA($user)) {
            if ($this->isSetupRoute($request)) {
                return $next($request);
            }

            return redirect()->route('2fa.setup');
        }

        return $next($request);
    }

    // Fortify's own two-factor.* and passkey.* endpoints (QR code, enable,
    // confirm, recovery codes, password re-confirmation) are what the setup
    // page itself calls to let a user actually complete setup — without
    // this they'd be bounced back to 2fa.setup on every one of those
    // requests and could never finish enabling anything.
    private function isSetupRoute(Request $request): bool
    {
        if ($request->routeIs('2fa.setup', 'logout', 'password.confirm', 'password.confirm.store')) {
            return true;
        }

        $name = $request->route()?->getName() ?? '';

        return str_starts_with($name, 'two-factor.') || str_starts_with($name, 'passkey.');
    }

    private function userHas2FA(User $user): bool
    {
        return $user->hasEnabledTwoFactorAuthentication();
    }
}
