<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Alteramos el ENUM para inyectar 'no_asistio' al final, manteniendo el valor por defecto
        DB::statement("ALTER TABLE reservations MODIFY COLUMN estado ENUM('pendiente', 'confirmada', 'completada', 'cancelada', 'no_asistio') DEFAULT 'pendiente' NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE reservations MODIFY COLUMN estado ENUM('pendiente', 'confirmada', 'completada', 'cancelada') DEFAULT 'pendiente' NOT NULL");
    }
};