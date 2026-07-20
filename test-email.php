<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$reserva = App\Models\Reservation::where('estado', 'confirmada')->first();
if ($reserva) {
    echo "Reserva encontrada: " . $reserva->id . "\n";
    try {
        Mail::to('test@test.com')->send(new \App\Mail\BarberReservationNotification($reserva));
        echo "Email de barbero generado correctamente.\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "No hay reservas confirmadas.\n";
}
