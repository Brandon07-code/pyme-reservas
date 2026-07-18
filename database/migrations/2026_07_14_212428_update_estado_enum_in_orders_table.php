<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Ya no se necesita: los valores del enum de orders
        // ya están correctos en la migración original de create_orders_table.
    }

    public function down(): void
    {
        //
    }
};