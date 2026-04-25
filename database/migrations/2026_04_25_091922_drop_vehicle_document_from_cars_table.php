<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (Schema::hasColumn('cars', 'vehicle_document')) {
                $table->dropColumn('vehicle_document');
            }
        });
    }

    /**
     * Reverse the migrations.
     * Original column was: $table->string('vehicle_document')->nullable();
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('vehicle_document')->nullable();
        });
    }
};
