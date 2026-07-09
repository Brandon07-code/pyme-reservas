<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    public function run(int $categoriaCortesId, int $categoriaRostroId): void
    {
        // CORTES JYM (Todos a $17.000)
        $cortes = [
            ['nombre' => 'Corte Clásico', 'duracion' => 30, 'imagen' => 'services/corte_clasico.jpg'],
            ['nombre' => 'Corte Siete', 'duracion' => 30, 'imagen' => 'services/corte_siete.jpg'],
            ['nombre' => 'Corte en Cuadros', 'duracion' => 30, 'imagen' => 'services/corte_cuadros.jpg'],
            ['nombre' => 'Degradado (Fade)', 'duracion' => 45, 'imagen' => 'services/degradado.jpg'],
            ['nombre' => 'Taper Fade', 'duracion' => 45, 'imagen' => 'services/taper_fade.jpg'],
            ['nombre' => 'Mullet', 'duracion' => 45, 'imagen' => 'services/mullet.jpg'],
            ['nombre' => 'Corte Militar', 'duracion' => 30, 'imagen' => 'services/corte_militar.jpg'],
            ['nombre' => 'Mohicano', 'duracion' => 45, 'imagen' => 'services/mohicano.jpg'],
            ['nombre' => 'Degradado en V', 'duracion' => 45, 'imagen' => 'services/degradado_v.jpg'],
        ];

        foreach ($cortes as $corte) {
            Service::create([
                'service_category_id' => $categoriaCortesId,
                'nombre' => $corte['nombre'],
                'precio' => 17000,
                'duracion_minutos' => $corte['duracion'],
                'imagen_url' => $corte['imagen'],
                'estado' => true
            ]);
        }

        // BARBA Y ROSTRO JYM
        $rostro = [
            ['nombre' => 'Barba Completa', 'precio' => 10000, 'duracion' => 15, 'imagen' => 'services/barba_completa.jpg'],
            ['nombre' => 'Perfilado de Bozo', 'precio' => 5000, 'duracion' => 15, 'imagen' => 'services/bozo.jpg'],
            ['nombre' => 'Mascarilla Sencilla', 'precio' => 5000, 'duracion' => 15, 'imagen' => 'services/mascarilla_sencilla.jpg'],
            ['nombre' => 'Mascarilla Hidratante', 'precio' => 10000, 'duracion' => 30, 'imagen' => 'services/mascarilla_hidratante.jpg'],
        ];

        foreach ($rostro as $servicio) {
            Service::create([
                'service_category_id' => $categoriaRostroId,
                'nombre' => $servicio['nombre'],
                'precio' => $servicio['precio'],
                'duracion_minutos' => $servicio['duracion'],
                'imagen_url' => $servicio['imagen'],
                'estado' => true
            ]);
        }
    }
}