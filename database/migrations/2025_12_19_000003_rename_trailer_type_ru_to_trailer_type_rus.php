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
            // Переименовываем поле trailer_type_ru в trailer_type_rus
            $table->renameColumn('trailer_type_ru', 'trailer_type_rus');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            // Возвращаем обратно
            $table->renameColumn('trailer_type_rus', 'trailer_type_ru');
        });
    }
};
