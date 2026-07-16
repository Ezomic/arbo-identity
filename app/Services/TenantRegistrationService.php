<?php

namespace App\Services;

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantRegistrationService
{
    /**
     * Create a new tenant (case management company) plus its one main
     * account — full admin rights over that tenant's Admin app from the
     * start, so there's always someone who can configure everything and
     * invite the rest of the team. No separate platform super-admin role
     * exists; ops/onboarding happens through this account per tenant.
     *
     * @param  array{tenant_name: string, name: string, username: string, email: string, password: string}  $data
     * @return array{tenant: Tenant, user: User}
     */
    public function register(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $tenant = Tenant::query()->create([
                'name' => $data['tenant_name'],
                'slug' => Str::slug($data['tenant_name']).'-'.Str::random(6),
                'status' => 'active',
            ]);

            $adminRole = Role::query()->where('app_slug', 'admin')->where('name', 'application_manager')->firstOrFail();

            $user = User::query()->create([
                'name' => $data['name'],
                'username' => $data['username'],
                'email' => $data['email'],
                'user_type_id' => 'application_manager',
                'role_id' => $adminRole->id,
                'tenant_id' => $tenant->id,
            ]);

            return ['tenant' => $tenant, 'user' => $user];
        });
    }
}
