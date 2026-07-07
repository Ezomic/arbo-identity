<?php

use App\Models\AppDefinition;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;

test('a platform admin can impersonate a regular user', function () {
    $admin = User::factory()->create(['user_type_id' => 'platform_admin']);

    $tenant = Tenant::query()->create(['name' => 'Acme Arbodienst', 'slug' => 'acme-arbo']);
    $role = Role::query()->create(['app_slug' => 'case-officers', 'name' => 'case_officer']);
    AppDefinition::query()->updateOrCreate(
        ['slug' => 'case-officers'],
        ['name' => 'Case Officers', 'base_url' => 'https://case-officers.test'],
    );

    $target = User::factory()->create([
        'user_type_id' => 'arbo',
        'role_id' => $role->id,
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($admin)->post(route('master.impersonate', $target->uuid));

    $response->assertRedirect();
    expect($response->headers->get('Location'))->toStartWith('https://case-officers.test/sso/callback');
});

test('impersonating another platform admin is rejected', function () {
    $admin = User::factory()->create(['user_type_id' => 'platform_admin']);
    $otherAdmin = User::factory()->create(['user_type_id' => 'platform_admin']);

    $response = $this->actingAs($admin)->post(route('master.impersonate', $otherAdmin->uuid));

    $response->assertForbidden();
});
