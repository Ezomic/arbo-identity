<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;

class TwoFactorSetupController extends Controller
{
    public function __construct(private readonly EnableTwoFactorAuthentication $enableTwoFactorAuthentication) {}

    public function show(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        // The page goes straight to "scan this QR code, confirm the code" —
        // there's no separate "enable" step in the UI — so a secret has to
        // exist before we can render one. EnableTwoFactorAuthentication is a
        // no-op once a secret is already set, confirmed or not.
        if (! $user->hasEnabledTwoFactorAuthentication()) {
            ($this->enableTwoFactorAuthentication)($user);
        }

        return Inertia::render('auth/TwoFactorSetup', [
            'hasPasskeys' => $user->passkeys()->exists(),
            'hasTotpEnabled' => $user->hasEnabledTwoFactorAuthentication(),
            'qrCodeSvg' => $user->hasEnabledTwoFactorAuthentication()
                ? null
                : $user->twoFactorQrCodeSvg(),
            'recoveryCodes' => $user->two_factor_recovery_codes
                ? json_decode(decrypt($user->two_factor_recovery_codes), true)
                : [],
        ]);
    }
}
