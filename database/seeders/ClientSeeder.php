<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Carbon\Carbon;

class ClientSeeder extends Seeder
{
    public function run()
    {
        $nombresColombianos = [
            ['Juan David', 'Osorio'], ['Carlos Andrés', 'Jaramillo'], ['Sebastián', 'Londoño'],
            ['Jhon Jairo', 'Castaño'], ['Andrés Felipe', 'Alzate'], ['Mateo', 'Quintero'],
            ['Daniel', 'Echeverri'], ['Santiago', 'Restrepo'], ['Camilo', 'Cardona'],
            ['Alejandro', 'Mejía'], ['Felipe', 'Toro'], ['Julián', 'Marín'],
            ['Nicolás', 'Hincapié'], ['Samuel', 'Arango'], ['Tomás', 'Gálvez'],
            ['Esteban', 'Pérez'], ['Cristian', 'Ramírez'], ['Brayan', 'García'],
            ['Diego', 'Rendón'], ['Mauricio', 'Ochoa'], ['Mariana', 'Gómez'],
            ['Valentina', 'López'], ['Camila', 'Giraldo'], ['Laura', 'Montoya'],
            ['Sara', 'Vásquez'], ['Daniela', 'Ríos'], ['Isabella', 'Zuluaga'],
            ['Salomé', 'Bedoya'], ['Manuela', 'Zapata'], ['Sofía', 'Ospina']
        ];

        // Mezclar aleatoriamente el array para que cada iteración del seeder sea algo única
        shuffle($nombresColombianos);

        $clientesCreados = [];

        foreach ($nombresColombianos as $index => $persona) {
            // Distribuir el created_at aleatoriamente en los últimos 6 meses
            // Restamos días aleatorios (entre 0 y 180) desde hoy
            $fechaRegistro = Carbon::now()->subDays(rand(0, 180));

            // Teléfono aleatorio empezando con 3 (Formato Colombia)
            $telefono = '3' . rand(10, 22) . rand(1000000, 9999999);
            
            // Email coherente sin tildes ni mayúsculas
            $nombreLimpio = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', str_replace(' ', '', $persona[0])));
            $apellidoLimpio = strtolower(iconv('UTF-8', 'ASCII//TRANSLIT', $persona[1]));
            $email = $nombreLimpio . '.' . $apellidoLimpio . '@correo.com';

            $clientesCreados[] = Client::create([
                'primer_nombre' => $persona[0],
                'primer_apellido' => $persona[1],
                'telefono' => $telefono,
                'email' => $email,
                'estado' => true,
                'created_at' => $fechaRegistro,
                'updated_at' => $fechaRegistro,
            ]);
        }

        // Retornar los clientes insertados (Collection)
        return collect($clientesCreados);
    }
}