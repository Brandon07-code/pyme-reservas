<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$r = App\Models\Reservation::where('estado', 'pendiente')->first();
if ($r) {
    $r->estado = 'confirmada';
    event(new App\Events\ReservationUpdated($r));
    echo "Event fired\n";
} else {
    echo "No pending reservation found\n";
}
