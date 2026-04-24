<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Data migration: NULL out passwords for existing driver accounts.
 *
 * Drivers authenticate exclusively via WhatsApp OTP; any password value on
 * their record is unreachable (the login path rejects drivers before bcrypt
 * compare) but is still a data-model smell. This cleans up historical hashes.
 *
 * down() is intentionally a no-op — we cannot restore the original hashes
 * (bcrypt is one-way). The column remains nullable from the earlier migration.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('role', 'driver')
            ->whereNotNull('password')
            ->update(['password' => null]);
    }

    public function down(): void
    {
        // Irreversible: original bcrypt hashes cannot be recovered.
    }
};
