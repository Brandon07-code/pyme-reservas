<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservation_service', function (Blueprint $table) {
            $table->foreignId('reservation_id')->constrained('reservations')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('services')->restrictOnDelete();
            $table->decimal('precio_historico', 10, 2);
            $table->integer('duracion_historica');
            $table->string('observaciones', 255)->nullable();
            $table->timestamps();

            $table->primary(['reservation_id', 'service_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservation_service');
    }
};