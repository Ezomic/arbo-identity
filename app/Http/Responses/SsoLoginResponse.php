<?php

namespace App\Http\Responses;

use App\Models\AppDefinition;
use App\Models\User;
use App\Services\JwtIssuer;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class SsoLoginResponse implements LoginResponseContract
{
    public function __construct(private readonly JwtIssuer $jwtIssuer) {}

    public function toResponse($request): Response
    {
        $appSlug = $request->string('app')->trim()->value();

        if ($appSlug === '') {
            return redirect()->intended(route('dashboard', absolute: false));
        }

        $app = AppDefinition::query()->find($appSlug);

        abort_unless($app !== null, 404, "Unknown app \"{$appSlug}\".");

        /** @var User $user */
        $user = $request->user();

        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        $token = $this->jwtIssuer->issueFor($user, $appSlug);

        $redirectTo = $this->safeRedirectTarget($request, $app);

        // Inertia::location() (not redirect()->away()): the login form is
        // submitted via Inertia's <Form>, which visits via fetch() — fetch
        // enforces CORS on this cross-origin hop to the target app.
        // location() makes the client do a real window.location navigation
        // instead, which isn't subject to CORS.
        return Inertia::location($redirectTo.(str_contains($redirectTo, '?') ? '&' : '?').'token='.$token);
    }

    /**
     * Only ever redirect back into the target app's own domain, never wherever
     * the query string says — an open-redirect guard on the one place this
     * service sends a browser to a URL it doesn't fully control itself.
     */
    private function safeRedirectTarget(Request $request, AppDefinition $app): string
    {
        $requested = $request->string('redirect_to')->trim()->value();

        if ($requested !== '' && $app->ownsUrl($requested)) {
            return $requested;
        }

        return rtrim($app->base_url, '/').'/sso/callback';
    }
}
