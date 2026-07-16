<?php

use App\Models\Tenant;
use App\Models\User;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;

beforeEach(function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());
});

test('a fresh user in a tenant that requires 2fa can load the setup page without crashing', function () {
    $tenant = Tenant::query()->create(['name' => 'Acme', 'slug' => 'acme', 'require_2fa' => true]);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $this->actingAs($user)
        ->get('/2fa/setup')
        ->assertOk()
        ->assertInertia(fn ($page) => $page
            ->component('auth/TwoFactorSetup')
            ->where('hasTotpEnabled', false)
            ->where('qrCodeSvg', fn ($svg) => is_string($svg) && $svg !== '')
        );

    expect($user->refresh()->two_factor_secret)->not->toBeNull();
});

test('a user with no 2fa method in a require_2fa tenant is redirected to setup from any other route', function () {
    $tenant = Tenant::query()->create(['name' => 'Acme', 'slug' => 'acme', 'require_2fa' => true]);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertRedirect(route('2fa.setup'));
});

test('the two-factor enable endpoint is reachable while setting up, not intercepted by the require-2fa redirect', function () {
    $tenant = Tenant::query()->create(['name' => 'Acme', 'slug' => 'acme', 'require_2fa' => true]);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);

    $this->actingAs($user)->get('/2fa/setup');

    // Fortify's own enable endpoint responds with back()->with('status', ...);
    // it happens to land back on 2fa/setup because that's the referring page
    // in a real browser flow — the actual signal that Require2FA let this
    // request through to Fortify's controller (rather than intercepting it
    // itself) is the session status flash, which only Fortify sets.
    $response = $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->post(route('two-factor.enable'));

    $response->assertSessionHas('status', Fortify::TWO_FACTOR_AUTHENTICATION_ENABLED);
});

test('a user who has since confirmed 2fa is no longer redirected', function () {
    $tenant = Tenant::query()->create(['name' => 'Acme', 'slug' => 'acme', 'require_2fa' => true]);
    $user = User::factory()->withTwoFactor()->create(['tenant_id' => $tenant->id]);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertOk();
});

test('a registered passkey alone does not satisfy require_2fa — TOTP is still required', function () {
    $tenant = Tenant::query()->create(['name' => 'Acme', 'slug' => 'acme', 'require_2fa' => true]);
    $user = User::factory()->create(['tenant_id' => $tenant->id]);
    $user->passkeys()->create([
        'name' => 'MacBook',
        'credential_id' => 'cred-1',
        'credential' => ['type' => 'public-key'],
    ]);

    $this->actingAs($user)
        ->withSession(['auth.password_confirmed_at' => time()])
        ->get(route('security.edit'))
        ->assertRedirect(route('2fa.setup'));
});
