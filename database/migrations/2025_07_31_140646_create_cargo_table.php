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
        Schema::create('cargo', function (Blueprint $table) {
            $table->id();
            $table->string('from_location'); // Откуда
            $table->string('to_location'); // Куда
            $table->string('cargo_type'); // Тип груза
            $table->decimal('volume', 10, 2); // Объем груза
            $table->decimal('weight', 10, 2); // Вес груза
            $table->datetime('ready_date'); // Дата и время готовности
            $table->text('comment')->nullable(); // Комментарий / контакт
            $table->enum('status', ['available', 'in_progress', 'delivered'])->default('available'); // Статус груза
            $table->foreignId('created_by')->constrained('users'); // Кто создал (сотрудник склада)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo');
    }
};
