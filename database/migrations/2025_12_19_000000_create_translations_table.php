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
        Schema::create('translations', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Ключ перевода');
            $table->text('rus')->comment('Перевод на русском');
            $table->text('kaz')->comment('Перевод на казахском');
            $table->text('chn')->comment('Перевод на китайском');
            $table->string('group')->default('general')->comment('Группа переводов');
            $table->text('description')->nullable()->comment('Описание для чего используется');
            $table->timestamps();
            
            $table->index(['key', 'group']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translations');
    }
};
