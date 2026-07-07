<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ClientFactory extends Factory
{
    public function definition(): array
    {
        // Fecha de creación distribuida aleatoriamente en los últimos 6 meses
        $fechaCreacion = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'primer_nombre' => fake()->firstName(),
            'segundo_nombre' => fake()->optional()->firstName(),
            'primer_apellido' => fake()->lastName(),
            'segundo_apellido' => fake()->optional()->lastName(),
            'telefono' => '3' . fake()->numerify('#########'), // Obliga a que empiece por 3 (Celular Colombia)
            'email' => fake()->unique()->safeEmail(),
            'estado' => true,
            'created_at' => $fechaCreacion,
            'updated_at' => $fechaCreacion,
        ];
    }
}