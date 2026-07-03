<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Login now happens by username, not email — multiple accounts (e.g.
     * a person's arbo account and their linked employer account) can share
     * the same email address, so email can no longer be the login key.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
        });

        $usedUsernames = [];

        DB::table('users')->orderBy('id')->get(['id', 'email'])->each(function ($user) use (&$usedUsernames) {
            $base = Str::slug(Str::before($user->email, '@'), '_') ?: 'user';
            $username = $base;
            $suffix = 1;

            while (in_array($username, $usedUsernames, true) || DB::table('users')->where('username', $username)->exists()) {
                $username = $base.'_'.$suffix++;
            }

            $usedUsernames[] = $username;

            DB::table('users')->where('id', $user->id)->update(['username' => $username]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable(false)->change();
            $table->unique('username');
            $table->dropUnique(['email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['username']);
            $table->unique('email');
            $table->dropColumn('username');
        });
    }
};
