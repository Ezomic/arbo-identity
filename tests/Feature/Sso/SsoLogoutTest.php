<?php

use App\Models\User;

test('logging out here ends the session and sends the browser to the login form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get('/sso/logout');

    $response->assertRedirect(route('login'));

    $this->assertGuest();
});
