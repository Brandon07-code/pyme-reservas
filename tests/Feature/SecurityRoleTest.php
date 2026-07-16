<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecurityRoleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_un_cliente_no_puede_acceder_al_panel_administrativo()
    {
        // 1. Crear un usuario con rol de Cliente (Rol 3)
        $cliente = User::factory()->create(['role_id' => 3]);

        // 2. Intentar acceder a una ruta de Administrador (/usuarios) estando logueado como cliente
        $response = $this->actingAs($cliente)->get('/usuarios');

        // 3. Afirmar que el middleware VerificarRol lo bloquea con 403 Forbidden
        $response->assertStatus(403);
    }

    public function test_un_visitante_es_redirigido_al_login()
    {
        // 1. Intentar entrar al dashboard sin iniciar sesión
        $response = $this->get('/dashboard');

        // 2. Afirmar que es expulsado a la pantalla de login
        $response->assertRedirect('/login');
    }
}
