<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('first_name')->nullable()->after('name');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone_number')->nullable()->after('email');
            $table->string('preferred_locale')->default('nl')->after('phone_number');
            $table->string('timezone')->default('Europe/Amsterdam')->after('preferred_locale');
            $table->timestamp('suspended_at')->nullable()->after('timezone');
            $table->boolean('must_change_password')->default(false)->after('suspended_at');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone_number',
                'preferred_locale',
                'timezone',
                'suspended_at',
                'must_change_password',
            ]);
        });
    }
};
