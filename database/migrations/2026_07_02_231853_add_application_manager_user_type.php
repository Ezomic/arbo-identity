<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * The Admin app's own user_type — the main account created alongside
     * every new tenant (see TenantRegistrationService).
     */
    public function up(): void
    {
        DB::table('user_types')->insertOrIgnore([
            'id' => 'application_manager',
            'name' => 'Application Manager',
            'app_slug' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('user_types')->where('id', 'application_manager')->delete();
    }
};
