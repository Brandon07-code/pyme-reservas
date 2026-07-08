<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\Schedule;

class RoleUserSeeder extends Seeder
{
    public function run(): array
    {
        // 1. ROLES
        $adminRole = Role::create(['nombre' => 'Administrador', 'descripcion' => 'Control total del sistema']);
        $employeeRole = Role::create(['nombre' => 'Empleado', 'descripcion' => 'Presta servicios de barbería']);

        // 2. ADMINISTRADOR
        User::create([
            'role_id' => $adminRole->id,
            'primer_nombre' => 'Admin',
            'primer_apellido' => 'Sistema',
            'email' => 'admin@pymereservas.com',
            'password' => bcrypt('password'), // La contraseña sigue siendo 'password' para pruebas
            'estado' => true,
        ]);

        // 3. BARBEROS COLOMBIANOS
        $datosBarberos = [
            [
                'nombre' => 'Jefferson', 'apellido' => 'Martinez', 'email' => 'jefferson@pymereservas.com',
                'telefono' => '3128901234', 'especialidad' => 'Barbero Senior', 'peso_citas' => 50
            ],
            [
                'nombre' => 'Andrés', 'apellido' => 'Gómez', 'email' => 'andres@pymereservas.com',
                'telefono' => '3004567891', 'especialidad' => 'Barbero Regular', 'peso_citas' => 35
            ],
            [
                'nombre' => 'Kevin', 'apellido' => 'Stiven', 'email' => 'kevin@pymereservas.com',
                'telefono' => '3157891234', 'especialidad' => 'Barbero Nuevo', 'peso_citas' => 15
            ]
        ];

        $barberos = [];

        foreach ($datosBarberos as $datos) {
            $user = User::create([
                'role_id' => $employeeRole->id,
                'primer_nombre' => $datos['nombre'],
                'primer_apellido' => $datos['apellido'],
                'email' => $datos['email'],
                'password' => bcrypt('password'),
                'estado' => true,
            ]);

            $empleado = Employee::create([
                'user_id' => $user->id,
                'telefono' => $datos['telefono'],
                'especialidad' => $datos['especialidad'],
                'direccion' => 'Cartago, Valle del Cauca',
                'estado' => true,
            ]);

            // Crear el horario laboral real: Lunes (1) a Sábado (6), 8 AM a 6 PM
            for ($d = 1; $d <= 6; $d++) {
                Schedule::create([
                    'employee_id' => $empleado->id,
                    'dia_semana' => $d,
                    'hora_inicio' => '08:00:00',
                    'hora_fin' => '18:00:00',
                    'disponible' => true
                ]);
            }

            // Inyectamos el "peso" en el objeto para que el motor de reservas sepa cuánto trabajo asignarle
            $empleado->peso_citas = $datos['peso_citas'];
            $barberos[] = $empleado;
        }

        // Retornamos el array de barberos para que el DatabaseSeeder pueda usarlos
        return $barberos;
    }
}