<?php

namespace App\Http\Responses;

use App\Models\AppDefinition;
use App\Models\User;
use App\Services\JwtIssuer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Laravel\Passkeys\Contracts\PasskeyLoginResponse as PasskeyLoginResponseContract;

class PasskeyLoginResponse implements PasskeyLoginResponseContract
{
    public function __construct(private readonly JwtIssuer $jwtIssuer) {}

    /**
     * Passkey verification is a fetch() call from @laravel/passkeys/vue, not
     * an Inertia visit, so this always answers in JSON — the client does a
     * real window.location navigation with the result (see Login.vue),
     * same as SsoLoginResponse does for the password flow.
     */
    public function toResponse($request): JsonResponse
    {
        $appSlug = $request->string('app')->trim()->value();

        if ($appSlug === '') {
            return response()->json([
                'redirect' => redirect()->intended(route('dashboard', absolute: false))->getTargetUrl(),
            ]);
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

        return response()->json([
            'redirect' => $redirectTo.(str_contains($redirectTo, '?') ? '&' : '?').'token='.$token,
        ]);
    }

    /**
     * Same open-redirect guard as SsoLoginResponse: only ever send the
     * browser into the target app's own domain.
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
