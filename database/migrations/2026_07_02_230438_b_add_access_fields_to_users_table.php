<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * These replace user_app_roles: since an account now belongs to
     * exactly one portal, its type/role/tenant/scope live directly on the
     * user row instead of a many-to-many pivot. Nullable at the DB level
     * (existing rows get backfilled in a follow-up data migration);
     * application code treats user_type_id/role_id as required going
     * forward.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('user_type_id')->nullable()->after('uuid');
            $table->foreign('user_type_id')->references('id')->on('user_types')->nullOnDelete();

            $table->foreignId('role_id')->nullable()->after('user_type_id')->constrained()->nullOnDelete();

            $table->uuid('tenant_id')->nullable()->after('role_id');
            $table->foreign('tenant_id')->references('id')->on('tenants')->nullOnDelete();

            $table->uuid('scope_id')->nullable()->after('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['user_type_id']);
            $table->dropForeign(['role_id']);
            $table->dropForeign(['tenant_id']);
            $table->dropColumn(['user_type_id', 'role_id', 'tenant_id', 'scope_id']);
        });
    }
};
