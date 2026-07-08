<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Añadir FK a clientes para futuro Portal Cliente
        Schema::table('clients', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
        });

        // 2. Añadir imagen a servicios
        Schema::table('services', function (Blueprint $table) {
            $table->string('imagen_url', 255)->nullable()->after('duracion_minutos');
        });

        // 3. Añadir marca, género e imagen a productos
        Schema::table('products', function (Blueprint $table) {
            $table->string('marca', 100)->nullable()->after('nombre');
            $table->string('genero', 50)->nullable()->after('marca');
            $table->string('imagen_url', 255)->nullable()->after('stock_actual');
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn('imagen_url');
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['marca', 'genero', 'imagen_url']);
        });
    }
};