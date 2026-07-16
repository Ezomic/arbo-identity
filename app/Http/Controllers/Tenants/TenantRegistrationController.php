<?php

namespace App\Http\Controllers\Tenants;

use App\Http\Controllers\Controller;
use App\Services\TenantRegistrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TenantRegistrationController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('tenants/Create');
    }

    public function store(Request $request, TenantRegistrationService $service): Response
    {
        $data = $request->validate([
            'tenant_name' => ['required', 'string', 'max:255', 'unique:tenants,name'],
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', 'unique:users,username'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);

        ['user' => $user] = $service->register($data);

        Auth::login($user);

        $request->session()->regenerate();
        $request->session()->passwordConfirmed();

        return Inertia::render('auth/EnrollPasskey');
    }
}
