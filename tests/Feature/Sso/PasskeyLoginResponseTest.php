<?php

use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Laravel\Passkeys\Contracts\PasskeyLoginResponse as PasskeyLoginResponseContract;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

test('a passkey login with no app param redirects into the dashboard route', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $response = app(PasskeyLoginResponseContract::class)
        ->toResponse(Request::create('/passkeys/login', 'POST'));

    expect($response->getData(true)['redirect'])->toBe(route('dashboard'));
});

test('a passkey login with an app param carries the same SSO handoff as password login', function () {
    $tenant = Tenant::query()->create(['name' => 'Acme Arbodienst', 'slug' => 'acme-arbo']);
    $role = Role::query()->create(['app_slug' => 'case-officers', 'name' => 'case_officer']);

    $user = User::factory()->create([
        'user_type_id' => 'arbo',
        'role_id' => $role->id,
        'tenant_id' => $tenant->id,
    ]);
    Auth::login($user);

    $request = Request::create('/passkeys/login', 'POST', [
        'app' => 'case-officers',
        'redirect_to' => 'https://case-officers.test/sso/callback',
    ]);
    $request->setUserResolver(fn () => $user);

    $response = app(PasskeyLoginResponseContract::class)->toResponse($request);

    $redirect = $response->getData(true)['redirect'];

    expect($redirect)->toStartWith('https://case-officers.test/sso/callback');

    $query = (string) parse_url($redirect, PHP_URL_QUERY);
    parse_str($query, $params);

    $publicKey = File::get(config('sso.public_key_path'));
    $claims = JWT::decode((string) $params['token'], new Key($publicKey, 'RS256'));

    expect($claims->aud)->toBe('case-officers')
        ->and($claims->sub)->toBe($user->uuid);
});

test('a passkey login rejects an unknown app slug', function () {
    $user = User::factory()->create();
    Auth::login($user);

    $request = Request::create('/passkeys/login', 'POST', ['app' => 'does-not-exist']);
    $request->setUserResolver(fn () => $user);

    app(PasskeyLoginResponseContract::class)->toResponse($request);
})->throws(NotFoundHttpException::class);
