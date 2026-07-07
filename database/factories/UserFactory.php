<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        // Fecha de creación distribuida aleatoriamente en los últimos 6 meses
        $fechaCreacion = fake()->dateTimeBetween('-6 months', 'now');

        return [
            'role_id' => 2, // Por defecto empleado
            'primer_nombre' => fake()->firstName(),
            'segundo_nombre' => fake()->optional()->firstName(),
            'primer_apellido' => fake()->lastName(),
            'segundo_apellido' => fake()->optional()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'estado' => true,
            'created_at' => $fechaCreacion,
            'updated_at' => $fechaCreacion,
        ];
    }
}