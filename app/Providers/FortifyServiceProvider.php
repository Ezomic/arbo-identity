<?php

namespace App\Providers;

use App\Actions\Fortify\AuthenticateLoginAttempt;
use App\Actions\Fortify\ResetUserPassword;
use App\Http\Responses\PasskeyLoginResponse;
use App\Http\Responses\SsoLoginResponse;
use App\Models\User;
use App\Services\ActivityLogger;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Laravel\Passkeys\Contracts\PasskeyLoginResponse as PasskeyLoginResponseContract;
use Symfony\Component\HttpFoundation\Response;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(LoginResponse::class, SsoLoginResponse::class);
        $this->app->singleton(PasskeyLoginResponseContract::class, PasskeyLoginResponse::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureActions();
        $this->configureViews();
        $this->configureRateLimiting();
    }

    /**
     * Configure Fortify actions.
     */
    private function configureActions(): void
    {
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);
        Fortify::authenticateUsing(fn (Request $request) => app(AuthenticateLoginAttempt::class)->handle($request));
    }

    /**
     * Configure Fortify views.
     */
    private function configureViews(): void
    {
        Fortify::loginView(fn (Request $request) => Inertia::render('auth/Login', [
            'canResetPassword' => Features::enabled(Features::resetPasswords()),
            'status' => $request->session()->get('status'),
            'app' => $request->query('app'),
            'redirectTo' => $request->query('redirect_to'),
            'testAccounts' => $this->testAccounts($request),
        ]));

        Fortify::resetPasswordView(fn (Request $request) => Inertia::render('auth/ResetPassword', [
            'email' => $request->email,
            'token' => $request->route('token'),
            'passwordRules' => Password::defaults()->toPasswordRulesString(),
        ]));

        Fortify::requestPasswordResetLinkView(fn (Request $request) => Inertia::render('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
        ]));

        Fortify::confirmPasswordView(fn () => Inertia::render('auth/ConfirmPassword'));

        Fortify::twoFactorChallengeView(fn () => Inertia::render('auth/TwoFactorChallenge'));
    }

    /**
     * Seeded demo accounts, offered as one-click logins on the login page.
     * Local env only — never expose this list (or the /dev-login route
     * it powers) outside local development.
     *
     * Only accounts that can actually reach the app the login page is
     * targeting (own user_type matches, or a linked account's does) are
     * offered — otherwise clicking one would hit JwtIssuer's "no access"
     * failure for an app-scoped login.
     *
     * @return array<int, array{username: string, label: string}>
     */
    private function testAccounts(Request $request): array
    {
        if (! app()->environment('local')) {
            return [];
        }

        $appSlug = $request->string('app')->trim()->value();

        return User::query()
            ->with('userType')
            ->get()
            ->filter(fn (User $user) => $appSlug === '' || $this->canReachApp($user, $appSlug))
            ->map(fn (User $user) => [
                'username' => $user->username,
                'label' => $user->name.' ('.($user->userType->name ?? $user->username).')',
            ])
            ->values()
            ->all();
    }

    private function canReachApp(User $user, string $appSlug): bool
    {
        if ($user->userType?->app_slug === $appSlug) {
            return true;
        }

        return $user->linkedUsers()->contains(
            fn (User $linked) => $linked->userType?->app_slug === $appSlug,
        );
    }

    /**
     * Configure rate limiting.
     */
    private function configureRateLimiting(): void
    {
        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())).'|'.$request->ip());

            return Limit::perMinute(5)->by($throttleKey)->response($this->logAndRespond('login'));
        });

        RateLimiter::for('passkeys', function (Request $request) {
            return Limit::perMinute(10)->by(
                ($request->input('credential.id') ?: $request->session()->getId()).'|'.$request->ip(),
            );
        });

        RateLimiter::for('password-reset', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response($this->logAndRespond('password_reset'));
        });

        RateLimiter::for('tenant-registration', function (Request $request) {
            return Limit::perHour(3)->by($request->ip())->response($this->logAndRespond('tenant_registration'));
        });

        RateLimiter::for('dev-login', function (Request $request) {
            return Limit::perMinute(10)->by($request->ip())->response($this->logAndRespond('dev_login'));
        });
    }

    /**
     * Rate-limit hits on public/auth endpoints are logged as they may
     * indicate an attack, on top of the standard 429 response.
     */
    private function logAndRespond(string $event): callable
    {
        return function (Request $request, array $headers) use ($event): Response {
            app(ActivityLogger::class)->log("rate_limit_hit.{$event}", null, ['path' => $request->path()]);

            return response('Too Many Attempts.', 429, $headers);
        };
    }
}
