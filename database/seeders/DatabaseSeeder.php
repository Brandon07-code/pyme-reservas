<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ServiceCategory;
use App\Models\ProductCategory;
use App\Models\Service;
use App\Models\Employee;
use App\Models\Client;
use App\Models\Reservation;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. PARAMETRIZACIÓN DEL ENTORNO DE PRUEBAS
        $mesesHistorial = 6;
        $diasFuturo = 15;
        $horaApertura = 8; // 8:00 AM
        $horaCierre = 18;  // 6:00 PM
        $intervaloMinutos = 15; 

        // 2. ORQUESTACIÓN DE SEEDERS SECUNDARIOS
        $catCortes = ServiceCategory::create(['nombre' => 'Cortes de Cabello', 'estado' => true]);
        $catRostro = ServiceCategory::create(['nombre' => 'Cuidado Facial', 'estado' => true]);
        $catPerfumes = ProductCategory::create(['nombre' => 'Perfumería Original', 'estado' => true]);

        $this->callWith(ServiceSeeder::class, ['categoriaCortesId' => $catCortes->id, 'categoriaRostroId' => $catRostro->id]);
        $this->callWith(ProductSeeder::class, ['categoriaPerfumesId' => $catPerfumes->id]);
        
        $this->call(RoleUserSeeder::class);
        $this->call(ClientSeeder::class);

        // CORRECCIÓN: Traemos los datos frescos directamente de la base de datos
        $barberos = Employee::all();
        $clientes = Client::all();
        $serviciosBase = Service::all();

        // Asignamos todos los servicios a cada barbero
        foreach ($barberos as $empleado) {
            $empleado->services()->attach($serviciosBase->pluck('id')->toArray());
            
            // Asignar peso manual según especialidad para el algoritmo
            if ($empleado->especialidad == 'Barbero Senior') $empleado->peso_citas = 50;
            elseif ($empleado->especialidad == 'Barbero Regular') $empleado->peso_citas = 35;
            else $empleado->peso_citas = 15;
        }

        // 3. MOTOR DE SIMULACIÓN DE RESERVAS (TETRIS TEMPORAL)
        $fechaInicio = Carbon::today()->subMonths($mesesHistorial);
        $fechaFin = Carbon::today()->addDays($diasFuturo);
        $hoy = Carbon::now(); 

        for ($fechaActual = clone $fechaInicio; $fechaActual->lte($fechaFin); $fechaActual->addDay()) {
            
            if ($fechaActual->isSunday()) continue;

            $disponibilidad = [];
            foreach ($barberos as $barbero) {
                $bloques = [];
                for ($h = $horaApertura; $h < $horaCierre; $h++) {
                    $bloques[sprintf("%02d:00:00", $h)] = true;
                    $bloques[sprintf("%02d:15:00", $h)] = true;
                    $bloques[sprintf("%02d:30:00", $h)] = true;
                    $bloques[sprintf("%02d:45:00", $h)] = true;
                }
                $disponibilidad[$barbero->id] = $bloques;
            }

            $citasDelDia = rand(15, 25);

            for ($c = 0; $c < $citasDelDia; $c++) {
                
                $cliente = $clientes->random();
                $servicio = $serviciosBase->random(); 
                
                $probBarbero = rand(1, 100);
                if ($probBarbero <= 50) $empleado = $barberos->where('peso_citas', 50)->first(); 
                elseif ($probBarbero <= 85) $empleado = $barberos->where('peso_citas', 35)->first(); 
                else $empleado = $barberos->where('peso_citas', 15)->first(); 

                $bloquesNecesarios = $servicio->duracion_minutos / $intervaloMinutos; 
                $horaAsignada = null;
                $bloquesHorarios = array_keys($disponibilidad[$empleado->id]);

                foreach ($bloquesHorarios as $index => $hora) {
                    $libre = true;
                    for ($b = 0; $b < $bloquesNecesarios; $b++) {
                        if (!isset($bloquesHorarios[$index + $b]) || !$disponibilidad[$empleado->id][$bloquesHorarios[$index + $b]]) {
                            $libre = false;
                            break;
                        }
                    }

                    if ($libre) {
                        $horaAsignada = $hora;
                        for ($b = 0; $b < $bloquesNecesarios; $b++) {
                            $disponibilidad[$empleado->id][$bloquesHorarios[$index + $b]] = false;
                        }
                        break;
                    }
                }

                if (!$horaAsignada) continue;

                $horaFinReserva = Carbon::parse($horaAsignada)->addMinutes($servicio->duracion_minutos)->format('H:i:s');

                $estado = 'pendiente';
                $fechaHoraFinCita = Carbon::parse($fechaActual->format('Y-m-d') . ' ' . $horaFinReserva);

                if ($hoy->greaterThanOrEqualTo($fechaHoraFinCita)) {
                    $dado = rand(1, 100);
                    if ($dado <= 85) $estado = 'completada';
                    elseif ($dado <= 95) $estado = 'cancelada';
                    else $estado = 'no_asistio';
                } else {
                    $estado = (rand(1, 100) <= 70) ? 'confirmada' : 'pendiente';
                }

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