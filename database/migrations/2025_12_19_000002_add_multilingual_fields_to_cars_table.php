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
            // Добавляем поля на трех языках для названий
            $table->string('brand_rus')->nullable()->after('brand')->comment('Марка автомобиля на русском');
            $table->string('brand_kaz')->nullable()->after('brand_rus')->comment('Марка автомобиля на казахском');
            $table->string('brand_chn')->nullable()->after('brand_kaz')->comment('Марка автомобиля на китайском');
            
            $table->string('model_rus')->nullable()->after('model')->comment('Модель автомобиля на русском');
            $table->string('model_kaz')->nullable()->after('model_rus')->comment('Модель автомобиля на казахском');
            $table->string('model_chn')->nullable()->after('model_kaz')->comment('Модель автомобиля на китайском');
            
            $table->string('trailer_type_kaz')->nullable()->after('trailer_type')->comment('Тип прицепа на казахском');
            $table->string('trailer_type_chn')->nullable()->after('trailer_type_kaz')->comment('Тип прицепа на китайском');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn([
                'brand_rus', 'brand_kaz', 'brand_chn',
                'model_rus', 'model_kaz', 'model_chn',
                'trailer_type_rus', 'trailer_type_kaz', 'trailer_type_chn'
            ]);
        });
    }
};
