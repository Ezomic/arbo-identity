<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * app_definitions was previously only seeded via DatabaseSeeder, but
     * the next migration (user_types) needs these rows to already exist
     * for its own FK — insertOrIgnore so this is a no-op against the real
     * dev database (already seeded) while still covering fresh test DBs,
     * which only run migrations, not seeders.
     */
    public function up(): void
    {
        $now = now();

        DB::table('app_definitions')->insertOrIgnore([
            ['slug' => 'case-officers', 'name' => 'Case Officers', 'base_url' => 'https://case-officers.test', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'employers', 'name' => 'Employers', 'base_url' => 'https://employers.test', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'doctors', 'name' => 'Doctors', 'base_url' => 'https://doctors.test', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'employees', 'name' => 'Employees', 'base_url' => 'https://employees.test', 'created_at' => $now, 'updated_at' => $now],
            ['slug' => 'admin', 'name' => 'Admin', 'base_url' => 'https://admin.test', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Idempotent seed only; nothing to reverse.
    }
};
