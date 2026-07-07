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
        // ==========================================
        // 1. PARAMETRIZACIÓN DEL ENTORNO DE PRUEBAS
        // ==========================================
        $totalClientes = 30;
        $mesesHistorial = 6;
        $diasFuturo = 15;
        $horaApertura = 8; // 8:00 AM
        $horaCierre = 18;  // 6:00 PM
        $intervaloMinutos = 30; // Citas cada 30 min

        // ==========================================
        // 2. CREACIÓN DE ENTIDADES BASE
        // ==========================================
        $adminRole = Role::create(['nombre' => 'Administrador', 'descripcion' => 'Control total del sistema']);
        $employeeRole = Role::create(['nombre' => 'Empleado', 'descripcion' => 'Presta servicios de barbería']);

        $catCortes = ServiceCategory::create(['nombre' => 'Cortes de Cabello']);
        $catBarba = ServiceCategory::create(['nombre' => 'Cuidado de Barba']);
        $catPerfumes = ProductCategory::create(['nombre' => 'Perfumería']);
        $catCuidado = ProductCategory::create(['nombre' => 'Cuidado Capilar']);

        // Servicios estandarizados en múltiplos de 30 min para encajar en la grilla perfecta
        $serviciosBase = [
            Service::create(['service_category_id' => $catCortes->id, 'nombre' => 'Corte Degradado (Fade)', 'precio' => 30000, 'duracion_minutos' => 30]),
            Service::create(['service_category_id' => $catCortes->id, 'nombre' => 'Corte Clásico', 'precio' => 25000, 'duracion_minutos' => 30]),
            Service::create(['service_category_id' => $catBarba->id, 'nombre' => 'Perfilado de Barba', 'precio' => 15000, 'duracion_minutos' => 30]),
            Service::create(['service_category_id' => $catBarba->id, 'nombre' => 'Ritual Premium', 'precio' => 40000, 'duracion_minutos' => 60]),
        ];

        // Productos con inventario forzado (unos altos y otros en alerta crítica <= 5)
        Product::create(['product_category_id' => $catPerfumes->id, 'nombre' => 'Loción Aftershave Premium', 'precio' => 85000, 'stock_actual' => 15]);
        Product::create(['product_category_id' => $catPerfumes->id, 'nombre' => 'Colonia Amaderada', 'precio' => 120000, 'stock_actual' => 3]); // Alerta
        Product::create(['product_category_id' => $catCuidado->id, 'nombre' => 'Cera Mate Moldeadora', 'precio' => 35000, 'stock_actual' => 25]);
        Product::create(['product_category_id' => $catCuidado->id, 'nombre' => 'Aceite para Barba', 'precio' => 28000, 'stock_actual' => 4]); // Alerta

        User::create([
            'role_id' => $adminRole->id,
            'primer_nombre' => 'Admin',
            'primer_apellido' => 'Sistema',
            'email' => 'admin@pymereservas.com',
            'password' => bcrypt('password'),
        ]);

        $clientes = Client::factory($totalClientes)->create();

        // Crear 3 empleados (Barberos)
        $barberos = [];
        for ($i = 1; $i <= 3; $i++) {
            $user = User::factory()->create(['role_id' => $employeeRole->id]);
            $empleado = Employee::create([
                'user_id' => $user->id,
                'telefono' => '300' . rand(1111111, 9999999),
                'especialidad' => ($i === 1) ? 'Barbero Master' : (($i === 2) ? 'Barbero Regular' : 'Barbero Junior'),
            ]);
            $empleado->services()->attach(collect($serviciosBase)->pluck('id')->toArray());
            
            // Horarios: Lunes(1) a Sábado(6)
            for ($d = 1; $d <= 6; $d++) {
                Schedule::create([
                    'employee_id' => $empleado->id,
                    'dia_semana' => $d,
                    'hora_inicio' => '08:00:00',
                    'hora_fin' => '18:00:00',
                ]);
            }
            $barberos[] = $empleado;
        }

        // ==========================================
        // 3. MOTOR DE SIMULACIÓN DE RESERVAS (LA MAGIA)
        // ==========================================
        $fechaInicio = Carbon::today()->subMonths($mesesHistorial);
        $fechaFin = Carbon::today()->addDays($diasFuturo);
        $hoy = Carbon::now(); // Fecha y hora exacta real

        // Iterar día por día
        for ($fechaActual = clone $fechaInicio; $fechaActual->lte($fechaFin); $fechaActual->addDay()) {
            
            // Descanso los domingos
            if ($fechaActual->isSunday()) continue;

            // Grilla diaria de disponibilidad por barbero (8:00 a 17:30)
            $disponibilidad = [];
            foreach ($barberos as $barbero) {
                $bloques = [];
                for ($h = $horaApertura; $h < $horaCierre; $h++) {
                    $bloques[sprintf("%02d:00:00", $h)] = true; // ej. 08:00:00
                    $bloques[sprintf("%02d:30:00", $h)] = true; // ej. 08:30:00
                }
                $disponibilidad[$barbero->id] = $bloques;
            }

            // Crear entre 5 y 12 citas por día para el negocio
            $citasDelDia = rand(5, 12);

            for ($c = 0; $c < $citasDelDia; $c++) {
                // 1. Asignar cliente aleatorio
                $cliente = $clientes->random();

                // 2. Asignar Barbero por probabilidad (Master 50%, Regular 35%, Junior 15%)
                $probBarbero = rand(1, 100);
                if ($probBarbero <= 50) $empleado = $barberos[0];
                elseif ($probBarbero <= 85) $empleado = $barberos[1];
                else $empleado = $barberos[2];

                // 3. Asignar Servicio por probabilidad (Fade 60%, Clásico 20%, Barba 15%, Premium 5%)
                $probServicio = rand(1, 100);
                if ($probServicio <= 60) $servicio = $serviciosBase[0]; // Fade (30m)
                elseif ($probServicio <= 80) $servicio = $serviciosBase[1]; // Clásico (30m)
                elseif ($probServicio <= 95) $servicio = $serviciosBase[2]; // Barba (30m)
                else $servicio = $serviciosBase[3]; // Premium (60m)

                $bloquesNecesarios = $servicio->duracion_minutos / $intervaloMinutos; // 1 o 2 bloques

                // 4. Buscar un bloque libre contiguo para el barbero
                $horaAsignada = null;
                $bloquesHorarios = array_keys($disponibilidad[$empleado->id]);

                foreach ($bloquesHorarios as $index => $hora) {
                    // Verificar si este bloque y el siguiente (si aplica) están libres
                    $libre = true;
                    for ($b = 0; $b < $bloquesNecesarios; $b++) {
                        if (!isset($bloquesHorarios[$index + $b]) || !$disponibilidad[$empleado->id][$bloquesHorarios[$index + $b]]) {
                            $libre = false;
                            break;
                        }
                    }

                    if ($libre) {
                        $horaAsignada = $hora;
                        // Marcar bloques como ocupados
                        for ($b = 0; $b < $bloquesNecesarios; $b++) {
                            $disponibilidad[$empleado->id][$bloquesHorarios[$index + $b]] = false;
                        }
                        break;
                    }
                }

                // Si no hubo espacio contiguo en todo el día, saltamos esta cita
                if (!$horaAsignada) continue;

                $horaFinReserva = Carbon::parse($horaAsignada)->addMinutes($servicio->duracion_minutos)->format('H:i:s');

                // 5. Lógica de Estados basada estrictamente en la línea del tiempo
                $estado = 'pendiente';
                $fechaHoraCita = Carbon::parse($fechaActual->format('Y-m-d') . ' ' . $horaAsignada);

                if ($fechaHoraCita->isPast()) {
                    // Si ya pasó: 85% completada, 15% cancelada
                    $estado = (rand(1, 100) <= 85) ? 'completada' : 'cancelada';
                } else {
                    // Si es futuro/hoy más tarde: 70% confirmada, 30% pendiente
                    $estado = (rand(1, 100) <= 70) ? 'confirmada' : 'pendiente';
                }

                // 6. Insertar Reserva y Pivote
                $reserva = Reservation::create([
                    'client_id' => $cliente->id,
                    'employee_id' => $empleado->id,
                    'fecha' => $fechaActual->format('Y-m-d'),
                    'hora_inicio' => $horaAsignada,
                    'hora_fin' => $horaFinReserva,
                    'estado' => $estado,
                    'total' => $servicio->precio,
                    'created_at' => clone $fechaActual,
                ]);

                $reserva->services()->attach($servicio->id, [
                    'precio_historico' => $servicio->precio,
                    'duracion_historica' => $servicio->duracion_minutos,
                ]);
            }
        }
    }
}