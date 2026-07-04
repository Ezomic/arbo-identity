<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TwoFactorSetupController extends Controller
{
    public function show(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        return Inertia::render('auth/TwoFactorSetup', [
            'hasPasskeys' => $user->passkeys()->exists(),
            'hasTotpEnabled' => $user->hasConfirmedTwoFactorAuthentication(),
            'qrCodeSvg' => $user->hasConfirmedTwoFactorAuthentication()
                ? null
                : $user->twoFactorQrCodeSvg(),
            'recoveryCodes' => $user->two_factor_recovery_codes
                ? json_decode(decrypt($user->two_factor_recovery_codes), true)
                : [],
        ]);
    }
}
