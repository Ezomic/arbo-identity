<?php

use App\Models\AppDefinition;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use App\Models\UserLink;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\File;

function seedAppDefinition(string $slug, string $baseUrl): AppDefinition
{
    return AppDefinition::query()->updateOrCreate(['slug' => $slug], ['name' => ucfirst($slug), 'base_url' => $baseUrl]);
}

function decodeSsoToken(string $location): object
{
    $query = (string) parse_url($location, PHP_URL_QUERY);
    parse_str($query, $params);

    $publicKey = File::get(config('sso.public_key_path'));

    return JWT::decode($params['token'], new Key($publicKey, 'RS256'));
}

test('unauthenticated visitors are sent on to the login form with app/redirect_to preserved', function () {
    seedAppDefinition('case-officers', 'https://case-officers.test');

    $response = $this->get('/sso/authorize?'.http_build_query([
        'app' => 'case-officers',
        'redirect_to' => 'https://case-officers.test/sso/callback',
    ]));

    $response->assertRedirect();
    $location = $response->headers->get('Location');

    expect($location)->toContain('/login')
        ->and($location)->toContain('app=case-officers')
        ->and($location)->toContain(urlencode('https://case-officers.test/sso/callback'));
});

test('an already-authenticated user is issued a token immediately, without hitting the login form', function () {
    seedAppDefinition('case-officers', 'https://case-officers.test');
    $tenant = Tenant::query()->create(['name' => 'Acme Arbodienst', 'slug' => 'acme-arbo']);
    $role = Role::query()->create(['app_slug' => 'case-officers', 'name' => 'case_officer']);
    $user = User::factory()->create([
        'user_type_id' => 'arbo',
        'role_id' => $role->id,
        'tenant_id' => $tenant->id,
    ]);

    $response = $this->actingAs($user)->get('/sso/authorize?'.http_build_query([
        'app' => 'case-officers',
        'redirect_to' => 'https://case-officers.test/sso/callback',
    ]));

    $response->assertRedirect();
    $location = $response->headers->get('Location');

    expect($location)->toStartWith('https://case-officers.test/sso/callback')
        ->and($location)->not->toContain('/login');

    $claims = decodeSsoToken($location);

    expect($claims->aud)->toBe('case-officers')
        ->and($claims->sub)->toBe($user->uuid);
});

test('rejects an unknown app slug', function () {
    $this->get('/sso/authorize?app=does-not-exist&redirect_to=https://example.test')
        ->assertNotFound();
});

test('switching to a linked account issues a token AS that account, not the logged-in one', function () {
    seedAppDefinition('case-officers', 'https://case-officers.test');
    seedAppDefinition('employers', 'https://employers.test');
    $tenant = Tenant::query()->create(['name' => 'Acme Arbodienst', 'slug' => 'acme-arbo']);
    $arboRole = Role::query()->create(['app_slug' => 'case-officers', 'name' => 'case_officer']);
    $employerRole = Role::query()->create(['app_slug' => 'employers', 'name' => 'employer_contact']);

    $caseOfficer = User::factory()->create([
        'name' => 'Casey Officer',
        'user_type_id' => 'arbo',
        'role_id' => $arboRole->id,
        'tenant_id' => $tenant->id,
    ]);

    $employerContact = User::factory()->create([
        'name' => 'Emma Employer',
        'user_type_id' => 'employer',
        'role_id' => $employerRole->id,
        'tenant_id' => $tenant->id,
    ]);

    UserLink::query()->create(['user_id' => $caseOfficer->id, 'linked_user_id' => $employerContact->id]);

    $response = $this->actingAs($caseOfficer)->get('/sso/authorize?'.http_build_query([
        'app' => 'employers',
        'redirect_to' => 'https://employers.test/sso/callback',
    ]));

    $claims = decodeSsoToken($response->headers->get('Location'));

    expect($claims->aud)->toBe('employers')
        ->and($claims->sub)->toBe($employerContact->uuid)
        ->and($claims->name)->toBe('Emma Employer')
        ->and($claims->sub)->not->toBe($caseOfficer->uuid);
});

test('a user with no access and no link to the target app is rejected', function () {
    seedAppDefinition('case-officers', 'https://case-officers.test');
    seedAppDefinition('employers', 'https://employers.test');
    $tenant = Tenant::query()->create(['name' => 'Acme Arbodienst', 'slug' => 'acme-arbo']);
    $arboRole = Role::query()->create(['app_slug' => 'case-officers', 'name' => 'case_officer']);

    $caseOfficer = User::factory()->create([
        'user_type_id' => 'arbo',
        'role_id' => $arboRole->id,
        'tenant_id' => $tenant->id,
    ]);

    $this->actingAs($caseOfficer)->get('/sso/authorize?'.http_build_query([
        'app' => 'employers',
        'redirect_to' => 'https://employers.test/sso/callback',
    ]))->assertStatus(500);
});
