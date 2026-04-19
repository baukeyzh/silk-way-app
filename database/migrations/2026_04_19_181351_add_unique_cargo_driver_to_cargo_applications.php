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
        Schema::table('cargo_applications', function (Blueprint $table) {
            $table->unique(['cargo_id', 'driver_id'], 'cargo_applications_cargo_id_driver_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargo_applications', function (Blueprint $table) {
            $table->dropUnique('cargo_applications_cargo_id_driver_id_unique');
        });
    }
};
