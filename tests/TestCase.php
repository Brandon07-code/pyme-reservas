<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\Role;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Seed basic roles for all tests since RefreshDatabase drops the DB
        \Illuminate\Support\Facades\DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'nombre' => 'Admin', 'descripcion' => 'Admin'],
            ['id' => 2, 'nombre' => 'Empleado', 'descripcion' => 'Empleado'],
            ['id' => 3, 'nombre' => 'Cliente', 'descripcion' => 'Cliente'],
        ]);
    }
}
