<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->uuid('user_id')->nullable()->index(); // nullable for failed login attempts
            $table->string('event'); // login, logout, sso_handoff, password_changed, 2fa_enabled, 2fa_disabled, profile_updated, account_suspended, failed_login
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->json('payload')->nullable(); // extra context, never contains sensitive values
            $table->string('checksum', 64)->nullable(); // HMAC for tamper-evidence
            $table->timestamp('created_at')->useCurrent();
            // No updated_at — this table is append-only
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
