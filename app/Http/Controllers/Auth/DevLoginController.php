<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\UserHasNoAccessToAppException;
use App\Http\Controllers\Controller;
use App\Http\Responses\SsoLoginResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Local-only shortcut for logging in as one of the seeded demo accounts
 * without typing credentials. Reuses SsoLoginResponse so the app/redirect_to
 * handoff behaves identically to a normal password login.
 */
class DevLoginController extends Controller
{
    public function store(Request $request, SsoLoginResponse $ssoLoginResponse): Response
    {
        abort_unless(app()->environment('local'), 404);

        $data = $request->validate([
            'username' => ['required', 'string', 'exists:users,username'],
        ]);

        Auth::login(User::query()->where('username', $data['username'])->firstOrFail());

        try {
            return $ssoLoginResponse->toResponse($request);
        } catch (UserHasNoAccessToAppException $e) {
            return back()->withErrors(['username' => $e->getMessage()]);
        }
    }
}
