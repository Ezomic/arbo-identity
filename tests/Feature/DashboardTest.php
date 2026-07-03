<?php

namespace Tests\Feature;

use App\Models\AppDefinition;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_the_login_page()
    {
        $response = $this->get(route('dashboard'));
        $response->assertRedirect(route('login'));
    }

    /**
     * Identity has no dashboard of its own — visiting it just forwards the
     * authenticated user straight into their own portal.
     */
    public function test_authenticated_users_are_sent_to_their_own_portal()
    {
        AppDefinition::query()->updateOrCreate(
            ['slug' => 'case-officers'],
            ['name' => 'Case Officers', 'base_url' => 'https://case-officers.test'],
        );

        $user = User::factory()->create(['user_type_id' => 'arbo']);
        $this->actingAs($user);

        $response = $this->get(route('dashboard'));

        $response->assertRedirect();
        $this->assertStringStartsWith(
            'https://case-officers.test/sso/callback',
            $response->headers->get('Location'),
        );
    }
}
