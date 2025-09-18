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
        Schema::create('cargo_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained('cargo')->onDelete('cascade');
            $table->foreignId('driver_id')->constrained('users');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('driver_notes')->nullable(); // Заметки водителя
            $table->text('warehouse_notes')->nullable(); // Заметки склада
            $table->string('contact_whatsapp')->nullable(); // WhatsApp контакт
            $table->string('contact_wechat')->nullable(); // WeChat контакт
            $table->string('pickup_contact')->nullable(); // Контакт в точке получения
            $table->string('pickup_address')->nullable(); // Адрес получения
            $table->string('delivery_contact')->nullable(); // Контакт в точке доставки
            $table->string('delivery_address')->nullable(); // Адрес доставки
            $table->datetime('approved_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargo_applications');
    }
};
