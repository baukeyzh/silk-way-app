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
        Schema::table('cargo', function (Blueprint $table) {
            $table->decimal('weight', 10, 2)->change();
            $table->decimal('volume', 10, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargo', function (Blueprint $table) {
            $table->decimal('weight', 8, 2)->change();
            $table->decimal('volume', 8, 2)->change();
        });
    }
};
