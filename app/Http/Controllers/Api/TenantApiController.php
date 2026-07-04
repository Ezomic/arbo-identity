<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TenantApiController extends Controller
{
    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'require_2fa' => ['required', 'boolean'],
        ]);

        $tenant = Tenant::query()->findOrFail($id);
        $tenant->update($data);

        return response()->json(['id' => $tenant->id, 'require_2fa' => $tenant->require_2fa]);
    }
}
