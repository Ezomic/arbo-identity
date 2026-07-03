<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Generic opaque scope id for roles that are narrower than a whole
     * tenant — e.g. an `employer_contact` role in the `employers` app is
     * scoped to one specific Employer record (owned by Case Officers, a
     * domain Identity has no other knowledge of). Identity just stores and
     * forwards this id in the JWT; it never interprets it.
     */
    public function up(): void
    {
        Schema::table('user_app_roles', function (Blueprint $table) {
            $table->uuid('scope_id')->nullable()->after('tenant_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_app_roles', function (Blueprint $table) {
            $table->dropColumn('scope_id');
        });
    }
};
