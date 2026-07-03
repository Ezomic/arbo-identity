<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * user_app_roles let one account hold roles in several apps at once —
     * that's no longer the model (see create_user_types_table). This is a
     * one-time, explicit fix-up of this dev database's seed data rather
     * than a generic pivot-to-columns algorithm: each user's first
     * user_app_roles row becomes their own account's fixed
     * type/role/tenant/scope, and any further rows are replaced by an
     * explicit user_links row to the *other* account that already
     * represents that access (Casey's extra "employers" grant becomes a
     * link to Emma's own employer account, instead of a second role on
     * Casey's account).
     */
    public function up(): void
    {
        $appSlugToUserType = [
            'case-officers' => 'arbo',
            'employers' => 'employer',
            'employees' => 'employee',
            'doctors' => 'medical_doctor',
        ];

        $rows = DB::table('user_app_roles')
            ->join('roles', 'roles.id', '=', 'user_app_roles.role_id')
            ->orderBy('user_app_roles.id')
            ->select('user_app_roles.*', 'roles.app_slug', 'roles.name as role_name')
            ->get();

        $primaryAssigned = [];

        foreach ($rows as $row) {
            $userType = $appSlugToUserType[$row->app_slug] ?? null;

            if ($userType === null) {
                continue;
            }

            if (! isset($primaryAssigned[$row->user_id])) {
                DB::table('users')->where('id', $row->user_id)->update([
                    'user_type_id' => $userType,
                    'role_id' => $row->role_id,
                    'tenant_id' => $row->tenant_id,
                    'scope_id' => $row->scope_id,
                ]);
                $primaryAssigned[$row->user_id] = $row->user_id;

                continue;
            }

            // A second row for a user we already gave primary fields to:
            // find the other account that already represents this
            // app/tenant/scope, and link to it instead of storing a
            // second role on this account.
            $otherUser = DB::table('users')
                ->where('user_type_id', $userType)
                ->where('tenant_id', $row->tenant_id)
                ->when($row->scope_id, fn ($q) => $q->where('scope_id', $row->scope_id))
                ->where('id', '!=', $row->user_id)
                ->first();

            if ($otherUser !== null) {
                DB::table('user_links')->insertOrIgnore([
                    'user_id' => $row->user_id,
                    'linked_user_id' => $otherUser->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // One-time data fix-up; nothing to reverse.
    }
};
