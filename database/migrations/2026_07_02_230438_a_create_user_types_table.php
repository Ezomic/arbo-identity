<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * A user_type is the fixed, intrinsic classification of an account
     * (employer, arbo, employee, medical_doctor) — it determines the ONE
     * portal that account natively belongs to. Access to OTHER portals
     * comes from linking to a different account of a different type
     * (see user_links), not from holding multiple roles on one account.
     */
    public function up(): void
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->string('app_slug');
            $table->foreign('app_slug')->references('slug')->on('app_definitions')->cascadeOnDelete();
            $table->timestamps();
        });

        $now = now();

        DB::table('user_types')->insert([
            ['id' => 'arbo', 'name' => 'Arbo (case officer)', 'app_slug' => 'case-officers', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 'employer', 'name' => 'Employer', 'app_slug' => 'employers', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 'employee', 'name' => 'Employee', 'app_slug' => 'employees', 'created_at' => $now, 'updated_at' => $now],
            ['id' => 'medical_doctor', 'name' => 'Medical doctor', 'app_slug' => 'doctors', 'created_at' => $now, 'updated_at' => $now],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_types');
    }
};
