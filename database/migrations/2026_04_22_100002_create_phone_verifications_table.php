<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phone_verifications', function (Blueprint $table) {
            $table->id();
            // Normalised phone digits only (e.g. 77001234567)
            $table->string('phone', 32)->index();
            // Extensible for future use-cases: 'driver_registration', 'password_reset', etc.
            $table->string('purpose', 32)->default('driver_registration');
            // bcrypt hash of the 6-digit OTP — never store plain codes
            $table->string('code_hash', 255);
            // Attempt counter; registration is blocked after 5 consecutive wrong codes
            $table->unsignedInteger('attempts')->default(0);
            // Nullable to satisfy MySQL strict mode (TIMESTAMP NOT NULL requires a DEFAULT).
            // Application code always populates these on create — nullability is a schema
            // detail, not a semantic one. expires_at is set to now()+10min, last_sent_at to now().
            $table->timestamp('expires_at')->nullable();
            // Used for the 60-second per-phone resend throttle (checked in application code)
            $table->timestamp('last_sent_at')->nullable();
            $table->timestamps();

            // One active verification per phone per purpose — upsert pattern
            $table->unique(['phone', 'purpose']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phone_verifications');
    }
};
