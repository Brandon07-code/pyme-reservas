<!DOCTYPE html>
<html>
<head>
    <title>Nueva Cita Asignada - Barbería JyM</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f5; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #D4AF37; color: #000; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .content { padding: 30px; }
        .content h2 { color: #0f172a; margin-top: 0; }
        .details-box { background-color: #f8fafc; border-left: 4px solid #0f172a; padding: 15px; margin: 20px 0; border-radius: 0 4px 4px 0; }
        .details-list { list-style: none; padding: 0; margin: 0; }
        .details-list li { padding: 5px 0; border-bottom: 1px solid #e2e8f0; }
        .details-list li:last-child { border-bottom: none; }
        .details-list strong { color: #0f172a; }
        .footer { background-color: #0f172a; color: #94a3b8; text-align: center; padding: 15px; font-size: 12px; }
        .btn { display: inline-block; background-color: #0f172a; color: #D4AF37; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nueva Cita Confirmada</h1>
        </div>
        <div class="content">
            <h2>Hola, {{ $reserva->employee->user->primer_nombre }}!</h2>
            
            <p>Se te ha confirmado una nueva cita en el sistema y requieres estar en la barbería a la hora programada.</p>

            <div class="details-box">
                <ul class="details-list">
                    <li><strong>Cliente:</strong> {{ $reserva->client->user?->primer_nombre ?? $reserva->client->primer_nombre }} {{ $reserva->client->user?->primer_apellido ?? $reserva->client->primer_apellido }}</li>
                    <li><strong>Día:</strong> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</li>
                    <li><strong>Hora:</strong> {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('h:i A') }} - {{ \Carbon\Carbon::parse($reserva->hora_fin)->format('h:i A') }}</li>
                    <li><strong>Servicios solicitados:</strong> 
                        @foreach($reserva->services as $servicio)
                            {{ $servicio->nombre }}@if(!$loop->last), @endif
                        @endforeach
                    </li>
                </ul>
            </div>

            <p>Por favor, asegúrate de tener tu estación de trabajo lista y preparada.</p>
            
            <a href="{{ url('/dashboard') }}" class="btn">Abrir Panel de Reservas</a>
        </div>
        <div class="footer">
            <p>Notificación interna automática de Barbería JyM.</p>
        </div>
    </div>
</body>
</html>
