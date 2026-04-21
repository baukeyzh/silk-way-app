<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Convert cargo.status and cargo_applications.status from MySQL ENUM to VARCHAR(20).
 *
 * WHY: ENUM requires a full ALTER TABLE (metadata lock) to extend with new values,
 * which blocks reads and writes on busy tables. All recent additions (e.g. cmr_status)
 * already use string columns. This migration aligns the legacy status columns with
 * that convention and also adds an index on cargo.status since scopeAvailable() and
 * scopeInProgress() are called on nearly every page load.
 *
 * We use raw DB::statement() rather than Schema::table()->change() because Laravel's
 * Blueprint->change() relies on doctrine/dbal which may not be installed, and
 * doctrine/dbal has known issues converting ENUM to string via Blueprint.
 */
return new class extends Migration
{
    public function up(): void
    {
        // cargo.status — was ENUM('available','in_progress','delivered') DEFAULT 'available'
        DB::statement(
            "ALTER TABLE cargo MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'available'"
        );

        // cargo_applications.status — was ENUM('pending','approved','rejected') DEFAULT 'pending'
        DB::statement(
            "ALTER TABLE cargo_applications MODIFY COLUMN status VARCHAR(20) NOT NULL DEFAULT 'pending'"
        );

        // Add index on cargo.status — used by scopeAvailable(), scopeInProgress(),
        // scopeDelivered() on virtually every cargo listing page.
        if (!$this->indexExists('cargo', 'cargo_status_index')) {
            Schema::table('cargo', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->index('status', 'cargo_status_index');
            });
        }
    }

    public function down(): void
    {
        // Drop the index first (before altering the column type)
        if ($this->indexExists('cargo', 'cargo_status_index')) {
            Schema::table('cargo', function (\Illuminate\Database\Schema\Blueprint $table) {
                $table->dropIndex('cargo_status_index');
            });
        }

        // Restore original ENUM definitions
        DB::statement(
            "ALTER TABLE cargo MODIFY COLUMN status ENUM('available','in_progress','delivered') NOT NULL DEFAULT 'available'"
        );

        DB::statement(
            "ALTER TABLE cargo_applications MODIFY COLUMN status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending'"
        );
    }

    private function indexExists(string $table, string $indexName): bool
    {
        $indexes = DB::select(
            "SHOW INDEX FROM `{$table}` WHERE Key_name = ?",
            [$indexName]
        );

        return count($indexes) > 0;
    }
};
