<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Actualizamos el ENUM para que acepte el nuevo estado inicial
        DB::statement("ALTER TABLE orders MODIFY COLUMN estado ENUM('pendiente', 'pendiente_recogida', 'entregado', 'cancelado') NOT NULL DEFAULT 'pendiente'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN estado ENUM('pendiente_recogida', 'entregado', 'cancelado') NOT NULL DEFAULT 'pendiente_recogida'");
    }
};