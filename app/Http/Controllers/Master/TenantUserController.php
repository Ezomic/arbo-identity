<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Mail\UserInvite;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantUserController extends Controller
{
    public function store(Request $request, string $tenantId): RedirectResponse
    {
        $tenant = Tenant::query()->findOrFail($tenantId);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'user_type_id' => ['required', 'string', Rule::exists('user_types', 'id')->whereNotNull('app_slug')],
            'scope_id' => ['nullable', 'uuid'],
        ]);

        /** @var UserType $userType */
        $userType = UserType::query()->findOrFail($data['user_type_id']);
        $role = Role::query()->where('app_slug', $userType->app_slug)->firstOrFail();

        /** @var User $user */
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => Str::slug($data['name']).'-'.Str::random(4),
            'password' => Str::password(32),
            'user_type_id' => $data['user_type_id'],
            'role_id' => $role->id,
            'tenant_id' => $tenant->id,
            'scope_id' => $data['scope_id'] ?? null,
        ]);

        $token = Password::broker()->createToken($user);
        $resetUrl = url(route('password.reset', ['token' => $token, 'email' => $user->email], false));

        Mail::to($user)->send(new UserInvite($user, $resetUrl));

        return back()->with('flash.success', "User created. An invite email has been sent to {$user->email}.");
    }

    public function destroy(string $tenantId, string $uuid): RedirectResponse
    {
        User::query()
            ->where('tenant_id', $tenantId)
            ->where('uuid', $uuid)
            ->firstOrFail()
            ->delete();

        return back()->with('flash.success', 'User deleted.');
    }
}
