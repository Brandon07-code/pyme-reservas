<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    // Regla: ¿Puede modificar/eliminar esta reserva?
    public function update(User $user, Reservation $reservation): bool
    {
        // 1. Si es Admin (Rol 1), puede hacer todo.
        if ($user->role_id == 1) {
            return true;
        }

        // 2. Si es Barbero (Rol 2), solo puede tocar las reservas asignadas a él.
        if ($user->role_id == 2 && $user->employee) {
            return $user->employee->id === $reservation->employee_id;
        }

        // 3. Cliente o sin permisos
        return false;
    }

    public function delete(User $user, Reservation $reservation): bool
    {
        return $this->update($user, $reservation); // Usa la misma regla de arriba
    }
}