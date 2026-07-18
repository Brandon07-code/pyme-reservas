<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Ya no se necesita: el estado 'no_asistio' fue incluido
        // directamente en la migración original de create_reservations_table.
    }

    public function down(): void
    {
        //
    }
};