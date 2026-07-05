<?php

use App\Mail\UserInvite;
use App\Models\Role;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

test('creating a tenant user sends an invite email instead of exposing a temporary password', function () {
    Mail::fake();

    $admin = User::factory()->create(['user_type_id' => 'platform_admin']);
    $tenant = Tenant::query()->create(['name' => 'Acme Arbodienst', 'slug' => 'acme-arbo']);
    Role::query()->create(['app_slug' => 'case-officers', 'name' => 'case_officer']);

    $response = $this->actingAs($admin)->post("/master/tenants/{$tenant->id}/users", [
        'name' => 'New Officer',
        'email' => 'new-officer@example.test',
        'user_type_id' => 'arbo',
    ]);

    $response->assertRedirect();
    $response->assertSessionHas('flash.success', 'User created. An invite email has been sent to new-officer@example.test.');
    expect(session('flash.success'))->not->toContain('Temporary password');

    $user = User::query()->where('email', 'new-officer@example.test')->firstOrFail();

    Mail::assertSent(UserInvite::class, function (UserInvite $mail) use ($user) {
        return $mail->user->is($user)
            && $mail->hasTo($user->email)
            && str_contains($mail->resetUrl, '/reset-password/')
            && str_contains($mail->resetUrl, urlencode($user->email));
    });
});
