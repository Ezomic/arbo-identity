<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Superseded by users.user_type_id/role_id/tenant_id/scope_id plus
     * user_links — see migrate_user_app_roles_to_users for where this
     * table's data went.
     */
    public function up(): void
    {
        Schema::dropIfExists('user_app_roles');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('user_app_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('app_slug');
            $table->foreign('app_slug')->references('slug')->on('app_definitions')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignUuid('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->uuid('scope_id')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'app_slug', 'tenant_id']);
        });
    }
};
