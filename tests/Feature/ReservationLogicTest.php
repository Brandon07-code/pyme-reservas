<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Role;
use App\Models\Client;
use App\Models\Employee;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Carbon\Carbon;

class ReservationLogicTest extends TestCase
{
    use RefreshDatabase;

    public function test_no_se_puede_editar_una_reserva_completada()
    {
        // 1. Entorno
        $admin = User::factory()->create(['role_id' => 1]);
        $cliente = Client::factory()->create();
        
        $empleadoUser = User::factory()->create(['role_id' => 1]);
        $empleado = Employee::create([
            'user_id' => $empleadoUser->id,
            'telefono' => '3000000000',
            'estado' => 1
        ]);

        // 2. Crear una reserva en el PASADO y marcarla COMPLETADA
        $reserva = Reservation::create([
            'client_id' => $cliente->id,
            'employee_id' => $empleado->id,
            'fecha' => Carbon::yesterday()->format('Y-m-d'),
            'hora_inicio' => '10:00:00',
            'hora_fin' => '10:30:00',
            'estado' => 'completada',
            'total' => 15000
        ]);

        // 3. El Admin intenta hacer trampa y mandarla a "Pendiente" enviando una petición PUT
        $response = $this->actingAs($admin)->put(route('reservas.update', $reserva), [
            'estado' => 'pendiente'
        ]);

        // 4. Afirmación: El Validador "UpdateReservationRequest" debe atraparlo (Lanza error en la sesión)
        $response->assertSessionHasErrors('estado');

        // Confirmar que en la base de datos sigue completada
        $reserva->refresh();
        $this->assertEquals('completada', $reserva->estado);
    }
}
