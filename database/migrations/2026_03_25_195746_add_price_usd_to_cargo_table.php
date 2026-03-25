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
            $table->decimal('price_usd', 10, 2)->nullable()->after('weight')->comment('Цена в USD');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargo', function (Blueprint $table) {
            $table->dropColumn('price_usd');
        });
    }
};
