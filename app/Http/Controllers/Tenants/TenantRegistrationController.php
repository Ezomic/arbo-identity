<?php

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Models\AppDefinition;
use App\Services\TenantRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class TenantRegistrationController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('tenants/Create');
    }

    public function store(Request $request, TenantRegistrationService $service): SymfonyResponse
    {
        $data = $request->validate([
            'tenant_name' => ['required', 'string', 'max:255', 'unique:tenants,name'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        ['user' => $user] = $service->register($data);

        Auth::login($user);

        $request->session()->regenerate();

        $adminApp = AppDefinition::query()->findOrFail('admin');

        return redirect()->route('sso.authorize', [
            'app' => 'admin',
            'redirect_to' => rtrim($adminApp->base_url, '/').'/sso/callback',
        ]);
    }
}
