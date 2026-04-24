<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Allow NULL on email so driver accounts don't require an email.
            // MySQL treats each NULL as distinct, so the unique index remains valid.
            $table->string('email')->nullable()->change();

            // Normalised phone (digits only, e.g. 77001234567).
            // Unique — MySQL allows multiple NULLs in a unique column.
            $table->string('phone', 32)->nullable()->unique()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->string('email')->nullable(false)->change();
        });
    }
};
