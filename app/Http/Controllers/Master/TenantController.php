<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TenantController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('master/tenants/Index', [
            'tenants' => Tenant::query()
                ->withCount('users')
                ->orderBy('name')
                ->get(['id', 'name', 'slug', 'status', 'require_2fa', 'created_at']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('master/tenants/Create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:63', 'regex:/^[a-z0-9-]+$/', 'unique:tenants,slug'],
            'status' => ['required', 'in:active,suspended'],
        ]);

        $tenant = Tenant::query()->create($data);

        return to_route('master.tenants.show', $tenant->id)
            ->with('flash.success', 'Tenant created.');
    }

    public function show(string $id): Response
    {
        $tenant = Tenant::query()->findOrFail($id);

        $users = User::query()
            ->where('tenant_id', $id)
            ->with(['userType:id,name', 'role:id,name'])
            ->latest()
            ->get(['id', 'uuid', 'name', 'email', 'user_type_id', 'role_id', 'scope_id', 'last_login_at', 'last_login_ip', 'created_at']);

        $userTypes = UserType::query()
            ->whereNotNull('app_slug')
            ->get(['id', 'name', 'app_slug']);

        return Inertia::render('master/tenants/Show', [
            'tenant' => $tenant,
            'users' => $users->map(fn ($u) => [
                'uuid' => $u->uuid,
                'name' => $u->name,
                'email' => $u->email,
                'user_type' => $u->userType?->name,
                'user_type_id' => $u->user_type_id,
                'role' => $u->role?->name,
                'scope_id' => $u->scope_id,
                'last_login_at' => $u->last_login_at,
                'last_login_ip' => $u->last_login_ip,
                'created_at' => $u->created_at,
            ]),
            'userTypes' => $userTypes,
        ]);
    }

    public function update(Request $request, string $id): RedirectResponse
    {
        $tenant = Tenant::query()->findOrFail($id);

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'status' => ['sometimes', 'required', 'in:active,suspended'],
            'require_2fa' => ['sometimes', 'required', 'boolean'],
        ]);

        $tenant->update($data);

        return back()->with('flash.success', 'Settings saved.');
    }
}
