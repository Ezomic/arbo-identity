<?php

namespace App\Services;

use App\Exceptions\UserHasNoAccessToAppException;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\File;

class JwtIssuer
{
    /**
     * Issue a short-lived RS256 handoff token for a target app, on behalf
     * of the authenticated user.
     *
     * Each account belongs to exactly one portal (its user_type). If the
     * authenticated user's own type doesn't match $appSlug, we look at
     * their linked accounts for one that does — and issue the token AS
     * THAT ACCOUNT (their own name/email/role/tenant/scope), not the
     * originally-authenticated one. "Switching portals" means switching
     * which account is acting, by design.
     */
    public function issueFor(User $authenticatedUser, string $appSlug): string
    {
        $targetUser = $this->resolveUserForApp($authenticatedUser, $appSlug);

        $now = time();

        $payload = [
            'iss' => 'identity',
            'aud' => $appSlug,
            'sub' => $targetUser->uuid,
            'iat' => $now,
            'exp' => $now + config('sso.token_ttl_seconds'),
            'email' => $targetUser->email,
            'name' => $targetUser->name,
            'role' => $targetUser->role?->name,
            'tenant_id' => $targetUser->tenant_id,
            'tenant_name' => $targetUser->tenant?->name,
            'scope_id' => $targetUser->scope_id,
            // Every portal reachable from the ORIGINALLY authenticated
            // account (itself + all linked accounts) — powers the "switch
            // portal" list. Deliberately only {slug, name, base_url} plus
            // which account you'd become, never role/tenant details.
            'apps' => $this->accessibleApps($authenticatedUser),
        ];

        return JWT::encode($payload, $this->privateKey(), 'RS256');
    }

    /**
     * The account that should represent this person in $appSlug: their
     * own, if it already belongs to that portal, otherwise a linked
     * account that does.
     */
    private function resolveUserForApp(User $authenticatedUser, string $appSlug): User
    {
        if ($authenticatedUser->userType?->app_slug === $appSlug) {
            return $authenticatedUser;
        }

        $linked = $authenticatedUser->linkedUsers()
            ->first(fn (User $candidate) => $candidate->userType?->app_slug === $appSlug);

        if ($linked !== null) {
            return $linked;
        }

        throw new UserHasNoAccessToAppException($appSlug);
    }

    /**
     * @return array<int, array{slug: string, name: string, base_url: string, as: string}>
     */
    private function accessibleApps(User $authenticatedUser): array
    {
        $accounts = $authenticatedUser->linkedUsers()->push($authenticatedUser)->unique('id');

        return $accounts
            ->filter(fn (User $account) => $account->userType !== null)
            ->map(fn (User $account) => [
                'slug' => $account->userType->app_slug,
                'name' => $account->userType->appDefinition->name,
                'base_url' => $account->userType->appDefinition->base_url,
                'as' => $account->name,
            ])
            ->values()
            ->all();
    }

    private function privateKey(): string
    {
        return File::get(config('sso.private_key_path'));
    }
}
