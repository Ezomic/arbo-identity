<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Fortify;

class AuthenticateLoginAttempt
{
    /**
     * Resolve the user for a login attempt, enforcing account lockout on top
     * of Fortify's existing per-username+IP rate limiter.
     *
     * A locked-out account is rejected before the password is even checked,
     * so a valid password never resets the clock early. A correct password
     * still resets the failed-attempt counter; a wrong one increments it and
     * locks the account once the configured threshold is reached.
     */
    public function handle(Request $request): ?User
    {
        $user = User::query()->where(Fortify::username(), $request->input(Fortify::username()))->first();

        if ($user === null) {
            return null;
        }

        if ($user->locked_until !== null && $user->locked_until->isFuture()) {
            throw ValidationException::withMessages([
                Fortify::username() => [__('This account is locked. Try again later.')],
            ]);
        }

        if (! Hash::check((string) $request->input('password'), $user->password)) {
            $this->registerFailedAttempt($user);

            return null;
        }

        if ($user->failed_login_count > 0 || $user->locked_until !== null) {
            $user->forceFill([
                'failed_login_count' => 0,
                'locked_until' => null,
            ])->save();
        }

        return $user;
    }

    private function registerFailedAttempt(User $user): void
    {
        $attempts = $user->failed_login_count + 1;

        $user->forceFill([
            'failed_login_count' => $attempts,
            'locked_until' => $attempts >= config('security.max_failed_login_attempts')
                ? now()->addMinutes(config('security.lockout_duration_minutes'))
                : $user->locked_until,
        ])->save();
    }
}
