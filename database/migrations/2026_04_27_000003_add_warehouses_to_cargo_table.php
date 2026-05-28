<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cargo', function (Blueprint $table) {
            // Nullable for backfill safety — new code enforces via FormRequest.
            $table->foreignId('from_warehouse_id')
                  ->nullable()
                  ->after('to_location_chn')
                  ->constrained('warehouses')
                  ->nullOnDelete();

            $table->foreignId('to_warehouse_id')
                  ->nullable()
                  ->after('from_warehouse_id')
                  ->constrained('warehouses')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('cargo', function (Blueprint $table) {
            $table->dropForeign(['from_warehouse_id']);
            $table->dropForeign(['to_warehouse_id']);
            $table->dropColumn(['from_warehouse_id', 'to_warehouse_id']);
        });
    }
};
