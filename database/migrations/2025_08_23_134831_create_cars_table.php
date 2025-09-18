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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('brand')->comment('Марка автомобиля');
            $table->string('model')->comment('Модель автомобиля');
            $table->string('license_plate')->comment('Гос. номер');
            $table->decimal('max_weight', 8, 2)->comment('Максимальный вес в тоннах');
            $table->decimal('trailer_length', 5, 2)->comment('Длина прицепа в метрах');
            $table->decimal('trailer_width', 5, 2)->comment('Ширина прицепа в метрах');
            $table->decimal('trailer_height', 5, 2)->comment('Высота прицепа в метрах');
            $table->decimal('trailer_volume', 8, 2)->comment('Объем прицепа в куб. метрах');
            $table->enum('trailer_type', ['tral', 'refrigerator', 'tent'])->comment('Тип прицепа');
            $table->string('trailer_type_ru')->comment('Тип прицепа на русском');
            $table->string('vehicle_document')->nullable()->comment('Путь к документу ПДД (PDF)');
            $table->boolean('is_active')->default(true)->comment('Активна ли машина');
            $table->timestamps();
            
            $table->index(['user_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
