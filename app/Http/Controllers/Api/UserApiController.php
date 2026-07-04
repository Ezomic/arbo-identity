<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UserApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'uuid'],
            'user_types' => ['required', 'array'],
            'user_types.*' => ['string', Rule::exists('user_types', 'id')],
        ]);

        $users = User::query()
            ->where('tenant_id', $data['tenant_id'])
            ->whereIn('user_type_id', $data['user_types'])
            ->with(['role:id,name,app_slug', 'userType:id,name,app_slug'])
            ->latest()
            ->get(['id', 'uuid', 'name', 'email', 'user_type_id', 'role_id', 'scope_id', 'created_at']);

        return response()->json($users->map(fn (User $user) => [
            'id' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'user_type_id' => $user->user_type_id,
            'user_type_name' => $user->userType?->name,
            'role_name' => $user->role?->name,
            'scope_id' => $user->scope_id,
            'created_at' => $user->created_at,
        ]));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'uuid'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'user_type_id' => ['required', 'string', Rule::exists('user_types', 'id')],
            'scope_id' => ['nullable', 'uuid'],
        ]);

        $userType = UserType::query()->findOrFail($data['user_type_id']);
        $role = Role::query()->where('app_slug', $userType->app_slug)->firstOrFail();

        $password = Str::password(12);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'username' => Str::slug($data['name']).'-'.Str::random(4),
            'password' => $password,
            'user_type_id' => $data['user_type_id'],
            'role_id' => $role->id,
            'tenant_id' => $data['tenant_id'],
            'scope_id' => $data['scope_id'] ?? null,
        ]);

        return response()->json([
            'id' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'user_type_id' => $user->user_type_id,
            'role_name' => $role->name,
            'scope_id' => $user->scope_id,
            'temporary_password' => $password,
        ], 201);
    }

    public function update(Request $request, string $uuid): JsonResponse
    {
        $user = User::query()->where('uuid', $uuid)->firstOrFail();

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'email' => ['sometimes', 'email', Rule::unique('users', 'email')->ignoreModel($user)],
            'scope_id' => ['sometimes', 'nullable', 'uuid'],
        ]);

        $user->update($data);

        return response()->json([
            'id' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'scope_id' => $user->scope_id,
        ]);
    }

    public function destroy(string $uuid): Response
    {
        User::query()->where('uuid', $uuid)->firstOrFail()->delete();

        return response()->noContent();
    }
}
