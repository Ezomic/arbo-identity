<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('user_types')->insertOrIgnore([
            'id' => 'platform_admin',
            'name' => 'Platform Admin',
            'app_slug' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('user_types')->where('id', 'platform_admin')->delete();
    }
};
