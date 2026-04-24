<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Makes users.password nullable so that driver accounts created via the
 * WhatsApp OTP flow can be stored without a password hash. Non-driver roles
 * (admin, warehouse_employee) continue to require a password at the
 * application layer; the DB column is intentionally permissive here.
 *
 * down() caveat: reverting to NOT NULL will fail loudly if any row currently
 * has password IS NULL, rather than silently truncating or corrupting data.
 * This is intentional — the caller must migrate those rows first.
 */
return new class extends Migration
{
    public function up(): void
    {
        // doctrine/dbal has historically been unreliable with NULL-ability changes
        // on the password column (it re-hashes casts interfere). Raw DDL is safer.
        DB::statement('ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NULL');
    }

    public function down(): void
    {
        // Guard: refuse to revert if any row would violate the NOT NULL constraint.
        $nullCount = DB::table('users')->whereNull('password')->count();

        if ($nullCount > 0) {
            throw new \RuntimeException(
                "Cannot revert make_password_nullable: {$nullCount} row(s) in `users` have password = NULL. " .
                'Populate those rows before rolling back this migration.'
            );
        }

        DB::statement('ALTER TABLE users MODIFY COLUMN password VARCHAR(255) NOT NULL');
    }
};
