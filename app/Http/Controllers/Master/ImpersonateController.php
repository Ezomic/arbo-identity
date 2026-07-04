<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\AppDefinition;
use App\Models\User;
use App\Services\ActivityLogger;
use App\Services\JwtIssuer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response;

class ImpersonateController extends Controller
{
    public function __construct(
        private readonly JwtIssuer $jwtIssuer,
        private readonly ActivityLogger $activityLogger,
    ) {}

    public function store(Request $request, string $uuid): Response
    {
        $targetUser = User::query()
            ->where('uuid', $uuid)
            ->with(['userType.appDefinition', 'role', 'tenant'])
            ->firstOrFail();

        $appSlug = $targetUser->userType?->app_slug;

        abort_if($appSlug === null, 422, 'This user has no portal to impersonate into.');

        $app = AppDefinition::query()->findOrFail($appSlug);

        $token = $this->jwtIssuer->issueForImpersonation($targetUser, $appSlug);

        /** @var User $admin */
        $admin = Auth::user();
        $this->activityLogger->log('impersonation', $admin->uuid, [
            'target_user' => $uuid,
            'target_app' => $appSlug,
        ]);

        $target = rtrim($app->base_url, '/').'/sso/callback';

        return Inertia::location($target.'?token='.$token);
    }
}
