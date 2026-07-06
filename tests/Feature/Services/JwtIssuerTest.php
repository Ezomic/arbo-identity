<?php

use App\Exceptions\AccountSuspendedException;
use App\Models\AppDefinition;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Services\JwtIssuer;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\File;

function makeArboUser(array $overrides = []): User
{
    AppDefinition::query()->updateOrCreate(['slug' => 'case-officers'], ['name' => 'Case Officers', 'base_url' => 'https://case-officers.test']);
    $tenant = Tenant::query()->create(['name' => 'Acme Arbodienst', 'slug' => 'acme-arbo']);
    $role = Role::query()->create(['app_slug' => 'case-officers', 'name' => 'case_officer']);

    return User::factory()->create(array_merge([
        'user_type_id' => 'arbo',
        'role_id' => $role->id,
        'tenant_id' => $tenant->id,
    ], $overrides));
}

function decodeIssuedToken(string $token): object
{
    $publicKey = File::get(config('sso.public_key_path'));

    return JWT::decode($token, new Key($publicKey, 'RS256'));
}

test('issueFor includes the account & security profile fields in the payload', function () {
    $user = makeArboUser([
        'first_name' => 'Casey',
        'last_name' => 'Officer',
        'phone_number' => '+31612345678',
        'preferred_locale' => 'en',
        'timezone' => 'Europe/London',
    ]);

    $token = app(JwtIssuer::class)->issueFor($user, 'case-officers');
    $claims = decodeIssuedToken($token);

    expect($claims->first_name)->toBe('Casey')
        ->and($claims->last_name)->toBe('Officer')
        ->and($claims->phone_number)->toBe('+31612345678')
        ->and($claims->preferred_locale)->toBe('en')
        ->and($claims->timezone)->toBe('Europe/London');
});

test('issueFor defaults preferred_locale and timezone per the schema defaults', function () {
    $user = makeArboUser()->refresh();

    $token = app(JwtIssuer::class)->issueFor($user, 'case-officers');
    $claims = decodeIssuedToken($token);

    expect($claims->preferred_locale)->toBe('nl')
        ->and($claims->timezone)->toBe('Europe/Amsterdam');
});

test('issueFor rejects a suspended user', function () {
    $user = makeArboUser(['suspended_at' => now()]);

    app(JwtIssuer::class)->issueFor($user, 'case-officers');
})->throws(AccountSuspendedException::class);

test('issueForImpersonation rejects a suspended user', function () {
    $user = makeArboUser(['suspended_at' => now()]);

    app(JwtIssuer::class)->issueForImpersonation($user, 'case-officers');
})->throws(AccountSuspendedException::class);
