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
            $table->text('ru')->nullable()->comment('Перевод на русском');
            $table->text('kz')->nullable()->comment('Перевод на казахском');
            $table->text('cn')->nullable()->comment('Перевод на китайском');
            $table->timestamps();
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
