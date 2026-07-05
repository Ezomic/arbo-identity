<?php

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;

function apiClientHeaders(): array
{
    config(['services.internal_api_token' => 'test-internal-token']);

    return ['Authorization' => 'Bearer test-internal-token'];
}

test('update refuses to touch a user belonging to a different tenant', function () {
    $tenantA = Tenant::query()->create(['name' => 'Tenant A', 'slug' => 'tenant-a']);
    $tenantB = Tenant::query()->create(['name' => 'Tenant B', 'slug' => 'tenant-b']);
    $user = User::factory()->create(['tenant_id' => $tenantB->id, 'name' => 'Original Name']);

    $this->putJson("/api/users/{$user->uuid}", [
        'tenant_id' => $tenantA->id,
        'name' => 'Hijacked Name',
    ], apiClientHeaders())->assertNotFound();

    expect($user->refresh()->name)->toBe('Original Name');
});

test('update succeeds when the tenant_id matches the user\'s own tenant', function () {
    $tenant = Tenant::query()->create(['name' => 'Tenant A', 'slug' => 'tenant-a']);
    $user = User::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Original Name']);

    $this->putJson("/api/users/{$user->uuid}", [
        'tenant_id' => $tenant->id,
        'name' => 'Updated Name',
    ], apiClientHeaders())->assertOk();

    expect($user->refresh()->name)->toBe('Updated Name');
});

test('destroy refuses to delete a user belonging to a different tenant', function () {
    $tenantA = Tenant::query()->create(['name' => 'Tenant A', 'slug' => 'tenant-a']);
    $tenantB = Tenant::query()->create(['name' => 'Tenant B', 'slug' => 'tenant-b']);
    $user = User::factory()->create(['tenant_id' => $tenantB->id]);

    $this->deleteJson("/api/users/{$user->uuid}", [
        'tenant_id' => $tenantA->id,
    ], apiClientHeaders())->assertNotFound();

    expect(User::query()->find($user->id))->not->toBeNull();
});

test('destroy succeeds when the tenant_id matches the user\'s own tenant', function () {
    $tenant = Tenant::query()->create(['name' => 'Tenant A', 'slug' => 'tenant-a']);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $this->deleteJson("/api/users/{$user->uuid}", [
        'tenant_id' => $tenant->id,
    ], apiClientHeaders())->assertNoContent();

    expect(User::query()->find($user->id))->toBeNull();
});

test('store rejects a user_type with no app_slug, such as platform_admin', function () {
    $tenant = Tenant::query()->create(['name' => 'Tenant A', 'slug' => 'tenant-a']);

    $this->postJson('/api/users', [
        'tenant_id' => $tenant->id,
        'name' => 'New User',
        'email' => 'new-user@example.test',
        'user_type_id' => 'platform_admin',
    ], apiClientHeaders())->assertUnprocessable();
});

test('store creates a user scoped to the requested tenant', function () {
    $tenant = Tenant::query()->create(['name' => 'Tenant A', 'slug' => 'tenant-a']);
    Role::query()->create(['app_slug' => 'case-officers', 'name' => 'case_officer']);

    $response = $this->postJson('/api/users', [
        'tenant_id' => $tenant->id,
        'name' => 'New User',
        'email' => 'new-user@example.test',
        'user_type_id' => 'arbo',
    ], apiClientHeaders());

    $response->assertCreated();
    expect(User::query()->where('email', 'new-user@example.test')->first()?->tenant_id)->toBe($tenant->id);
});
