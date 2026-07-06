<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use App\Models\Client;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\Schedule;
use App\Models\Reservation;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Crear Roles
        $adminRole = Role::create(['nombre' => 'Administrador', 'descripcion' => 'Control total del sistema']);
        $employeeRole = Role::create(['nombre' => 'Empleado', 'descripcion' => 'Presta servicios de barbería']);

        // 2. Crear Categorías de Servicios y Productos
        $catCortes = ServiceCategory::create(['nombre' => 'Cortes de Cabello']);
        $catBarba = ServiceCategory::create(['nombre' => 'Cuidado de Barba']);
        $catPerfumes = ProductCategory::create(['nombre' => 'Perfumería']);
        $catCuidado = ProductCategory::create(['nombre' => 'Cuidado Capilar']);

        // 3. Crear Servicios de Barbería Reales
        $services = collect([
            Service::create(['service_category_id' => $catCortes->id, 'nombre' => 'Corte Clásico', 'precio' => 25000, 'duracion_minutos' => 30]),
            Service::create(['service_category_id' => $catCortes->id, 'nombre' => 'Corte Degradado (Fade)', 'precio' => 30000, 'duracion_minutos' => 45]),
            Service::create(['service_category_id' => $catBarba->id, 'nombre' => 'Perfilado de Barba', 'precio' => 15000, 'duracion_minutos' => 20]),
            Service::create(['service_category_id' => $catBarba->id, 'nombre' => 'Ritual de Barba con Toalla Caliente', 'precio' => 25000, 'duracion_minutos' => 30]),
        ]);

        // 4. Crear Productos
        Product::create(['product_category_id' => $catPerfumes->id, 'nombre' => 'Loción Aftershave Premium', 'precio' => 85000, 'stock_actual' => 15]);
        Product::create(['product_category_id' => $catCuidado->id, 'nombre' => 'Cera Mate Moldeadora', 'precio' => 35000, 'stock_actual' => 25]);

        // 5. Crear Administrador Principal
        User::create([
            'role_id' => $adminRole->id,
            'primer_nombre' => 'Admin',
            'primer_apellido' => 'Sistema',
            'email' => 'admin@pymereservas.com',
            'password' => bcrypt('password'),
        ]);

        // 6. Crear Empleados (5 Barberos)
        $users = User::factory(5)->create(['role_id' => $employeeRole->id]);
        foreach ($users as $user) {
            $employee = Employee::create([
                'user_id' => $user->id,
                'telefono' => '300' . rand(1111111, 9999999),
                'especialidad' => 'Barbero Profesional',
            ]);

            // Asignar servicios que sabe hacer (aleatoriamente 2 o 3)
            $employee->services()->attach($services->random(rand(2, 4))->pluck('id')->toArray());

            // Crear horario (Lunes a Sábado, 8 AM a 6 PM)
            for ($i = 1; $i <= 6; $i++) {
                Schedule::create([
                    'employee_id' => $employee->id,
                    'dia_semana' => $i,
                    'hora_inicio' => '08:00:00',
                    'hora_fin' => '18:00:00',
                ]);
            }
        }

        // 7. Crear Clientes (30)
        $clients = Client::factory(30)->create();
        $employees = Employee::all();

        // 8. Crear Reservas de prueba (50)
        for ($i = 0; $i < 50; $i++) {
            $client = $clients->random();
            $employee = $employees->random();
            
            // Asignar fecha aleatoria en el mes actual y hora entre 9 AM y 4 PM
            $fecha = Carbon::now()->addDays(rand(-15, 15))->format('Y-m-d');
            $hora_inicio = Carbon::createFromTime(rand(9, 16), 0, 0);
            
            // Elegir 1 o 2 servicios que el empleado sepa hacer
            $serviciosAsignados = $employee->services->random(rand(1, 2));
            
            // Calcular totales
            $duracionTotal = $serviciosAsignados->sum('duracion_minutos');
            $precioTotal = $serviciosAsignados->sum('precio');
            $hora_fin = (clone $hora_inicio)->addMinutes($duracionTotal);

            // Crear la reserva evitando duplicidad en el mismo horario
            $reserva = Reservation::firstOrCreate(
                ['employee_id' => $employee->id, 'fecha' => $fecha, 'hora_inicio' => $hora_inicio->format('H:i:s')],
                [
                    'client_id' => $client->id,
                    'hora_fin' => $hora_fin->format('H:i:s'),
                    'estado' => rand(0, 1) ? 'completada' : 'pendiente',
                    'total' => $precioTotal,
                ]
            );

            // Poblar tabla pivote de servicios de la reserva
            if ($reserva->wasRecentlyCreated) {
                foreach ($serviciosAsignados as $servicio) {
                    $reserva->services()->attach($servicio->id, [
                        'precio_historico' => $servicio->precio,
                        'duracion_historica' => $servicio->duracion_minutos,
                    ]);
                }
            }
        }
    }
}