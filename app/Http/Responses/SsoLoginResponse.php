<?php

namespace App\Http\Responses;

use App\Models\AppDefinition;
use App\Models\User;
use App\Services\JwtIssuer;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
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

        $token = $this->jwtIssuer->issueFor($user, $appSlug);

        $redirectTo = $this->safeRedirectTarget($request, $app->base_url);

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
    private function safeRedirectTarget(Request $request, string $baseUrl): string
    {
        $requested = $request->string('redirect_to')->trim()->value();

        if ($requested !== '' && Str::startsWith($requested, $baseUrl)) {
            return $requested;
        }

        return rtrim($baseUrl, '/').'/sso/callback';
    }
}
