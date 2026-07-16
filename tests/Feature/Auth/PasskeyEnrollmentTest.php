<?php

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Inertia\Testing\AssertableInertia as Assert;

test('a valid signed enrollment link logs the user in and marks the session password-confirmed', function () {
    $user = User::factory()->create();

    $url = URL::temporarySignedRoute('passkey.enroll', now()->addHours(24), ['user' => $user->id]);

    $response = $this->get($url);

    $response->assertOk()->assertInertia(fn (Assert $page) => $page->component('auth/EnrollPasskey'));

    $this->assertAuthenticatedAs($user);
    expect(session('auth.password_confirmed_at'))->not->toBeNull();
});

test('an enrollment link lets the freshly-logged-in user reach the passkey registration routes', function () {
    $user = User::factory()->create();

    $url = URL::temporarySignedRoute('passkey.enroll', now()->addHours(24), ['user' => $user->id]);

    $this->get($url);

    $this->get(route('passkey.registration-options'))->assertOk();
});

test('an expired enrollment link is rejected', function () {
    $user = User::factory()->create();

    $url = URL::temporarySignedRoute('passkey.enroll', now()->subHour(), ['user' => $user->id]);

    $this->get($url)->assertForbidden();

    $this->assertGuest();
});

test('a tampered enrollment link is rejected', function () {
    $user = User::factory()->create();

    $url = URL::temporarySignedRoute('passkey.enroll', now()->addHours(24), ['user' => $user->id]);

    $this->get($url.'&tampered=1')->assertForbidden();

    $this->assertGuest();
});
