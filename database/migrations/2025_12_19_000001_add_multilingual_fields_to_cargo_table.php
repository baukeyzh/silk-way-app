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
            // Добавляем поля на трех языках для названий
            $table->string('from_location_rus')->nullable()->after('from_location')->comment('Откуда на русском');
            $table->string('from_location_kaz')->nullable()->after('from_location_rus')->comment('Откуда на казахском');
            $table->string('from_location_chn')->nullable()->after('from_location_kaz')->comment('Откуда на китайском');
            
            $table->string('to_location_rus')->nullable()->after('to_location')->comment('Куда на русском');
            $table->string('to_location_kaz')->nullable()->after('to_location_rus')->comment('Куда на казахском');
            $table->string('to_location_chn')->nullable()->after('to_location_kaz')->comment('Куда на китайском');
            
            $table->string('cargo_type_rus')->nullable()->after('cargo_type')->comment('Тип груза на русском');
            $table->string('cargo_type_kaz')->nullable()->after('cargo_type_rus')->comment('Тип груза на казахском');
            $table->string('cargo_type_chn')->nullable()->after('cargo_type_kaz')->comment('Тип груза на китайском');
            
            $table->text('comment_rus')->nullable()->after('comment')->comment('Комментарий на русском');
            $table->text('comment_kaz')->nullable()->after('comment_rus')->comment('Комментарий на казахском');
            $table->text('comment_chn')->nullable()->after('comment_kaz')->comment('Комментарий на китайском');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cargo', function (Blueprint $table) {
            $table->dropColumn([
                'from_location_rus', 'from_location_kaz', 'from_location_chn',
                'to_location_rus', 'to_location_kaz', 'to_location_chn',
                'cargo_type_rus', 'cargo_type_kaz', 'cargo_type_chn',
                'comment_rus', 'comment_kaz', 'comment_chn'
            ]);
        });
    }
};
