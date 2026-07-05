<?php

namespace App\Http\Controllers\Sso;

use App\Http\Controllers\Controller;
use App\Models\AppDefinition;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\JwtIssuer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class SsoAuthorizeController extends Controller
{
    public function __construct(
        private readonly JwtIssuer $jwtIssuer,
        private readonly ActivityLogger $activityLogger,
    ) {}

    /**
     * The single entry point every consuming app's "Log in" link points at.
     * Also doubles as Identity's own `home`/`dashboard` routes — Identity
     * has no page of its own to land on, so an authenticated user hitting
     * either just gets sent straight into their own portal (no `app` param
     * needed in that case; it's inferred from their user_type).
     *
     * Deliberately NOT behind `guest` middleware: if this were the Fortify
     * login route (guarded by `guest`), an already-authenticated Identity
     * session would get redirected to Identity's own home route before ever
     * looking at `app`/`redirect_to` — which breaks switching to a second
     * portal while already logged in here. This route branches instead:
     * already logged in → issue the token immediately; not logged in →
     * hand off to the normal Fortify login form, which resumes this same
     * handoff via SsoLoginResponse after a successful password login.
     */
    public function __invoke(Request $request): Response
    {
        $appSlug = $request->string('app')->trim()->value();
        $redirectTo = $request->string('redirect_to')->trim()->value();

        if ($appSlug === '' && Auth::check()) {
            /** @var User $authenticatedUser */
            $authenticatedUser = Auth::user();

            if ($authenticatedUser->user_type_id === 'platform_admin') {
                return Inertia::location('/master');
            }

            $appSlug = $authenticatedUser->userType->app_slug ?? '';
        }

        abort_if($appSlug === '', 400, 'Missing "app" parameter.');

        $app = AppDefinition::query()->find($appSlug);

        abort_unless($app !== null, 404, "Unknown app \"{$appSlug}\".");

        if (! Auth::check()) {
            return Inertia::location('/login?'.http_build_query([
                'app' => $appSlug,
                'redirect_to' => $redirectTo,
            ]));
        }

        /** @var User $user */
        $user = Auth::user();

        $token = $this->jwtIssuer->issueFor($user, $appSlug);

        $this->activityLogger->log('sso_handoff', $user->uuid ?? null, ['target_app' => $appSlug]);

        $target = $this->safeRedirectTarget($redirectTo, $app->base_url);

        return Inertia::location($target.(str_contains($target, '?') ? '&' : '?').'token='.$token);
    }

    /**
     * Same open-redirect guard as SsoLoginResponse: only ever send the
     * browser into the target app's own domain.
     */
    private function safeRedirectTarget(string $redirectTo, string $baseUrl): string
    {
        if ($redirectTo !== '' && Str::startsWith($redirectTo, $baseUrl)) {
            return $redirectTo;
        }

        return rtrim($baseUrl, '/').'/sso/callback';
    }
}
