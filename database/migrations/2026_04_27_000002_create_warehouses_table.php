<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_rus');
            $table->string('name_kaz')->nullable();
            $table->string('name_chn')->nullable();
            $table->string('address');
            $table->string('phone', 50)->nullable();
            $table->foreignId('city_id')->constrained('cities')->cascadeOnDelete();
            // nullable to allow legacy backfill where no creator is known
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('city_id');
            $table->index('created_by');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
