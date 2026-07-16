<?php

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('creating an organization creates a tenant and one fully-privileged admin account, and starts passkey enrollment', function () {
    Role::query()->create(['app_slug' => 'admin', 'name' => 'application_manager']);

    $response = $this->post('/tenants', [
        'tenant_name' => 'Acme Test BV',
        'name' => 'Ada Admin',
        'username' => 'ada',
        'email' => 'ada@acme-test.test',
    ]);

    $response->assertOk()->assertInertia(fn (Assert $page) => $page->component('auth/EnrollPasskey'));

    $tenant = Tenant::query()->where('name', 'Acme Test BV')->first();
    expect($tenant)->not->toBeNull();

    $user = User::query()->where('email', 'ada@acme-test.test')->first();
    expect($user)->not->toBeNull()
        ->and($user->user_type_id)->toBe('application_manager')
        ->and($user->tenant_id)->toBe($tenant->id)
        ->and($user->role->name)->toBe('application_manager')
        ->and($user->password)->toBeNull();

    $this->assertAuthenticatedAs($user);
    expect(session('auth.password_confirmed_at'))->not->toBeNull();
});

test('tenant name must be unique', function () {
    Role::query()->create(['app_slug' => 'admin', 'name' => 'application_manager']);
    Tenant::query()->create(['name' => 'Existing Co', 'slug' => 'existing-co']);

    $this->post('/tenants', [
        'tenant_name' => 'Existing Co',
        'name' => 'Ada Admin',
        'username' => 'ada',
        'email' => 'ada@acme-test.test',
    ])->assertSessionHasErrors('tenant_name');
});
