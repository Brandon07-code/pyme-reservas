<!DOCTYPE html>
<html>
<head>
    <title>Notificación de Cita - Barbería JyM</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f5; color: #333; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .header { background-color: #0f172a; color: #D4AF37; padding: 20px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .content { padding: 30px; }
        .content h2 { color: #0f172a; margin-top: 0; }
        .details-box { background-color: #f8fafc; border-left: 4px solid #D4AF37; padding: 15px; margin: 20px 0; border-radius: 0 4px 4px 0; }
        .details-list { list-style: none; padding: 0; margin: 0; }
        .details-list li { padding: 5px 0; border-bottom: 1px solid #e2e8f0; }
        .details-list li:last-child { border-bottom: none; }
        .details-list strong { color: #0f172a; }
        .footer { background-color: #0f172a; color: #94a3b8; text-align: center; padding: 15px; font-size: 12px; }
        .btn { display: inline-block; background-color: #D4AF37; color: #000; text-decoration: none; padding: 10px 20px; border-radius: 4px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Barbería JyM</h1>
        </div>
        <div class="content">
            <h2>Hola, {{ $reserva->client->primer_nombre }}!</h2>
            
            @if($action === 'creada')
                <p>Tu cita ha sido agendada con éxito en nuestro sistema y está en estado <strong>Pendiente</strong>.</p>
            @elseif($action === 'actualizada')
                <p>Ha habido una actualización en el estado de tu cita. El nuevo estado es: <strong>{{ ucfirst($reserva->estado) }}</strong>.</p>
            @endif

            <div class="details-box">
                <ul class="details-list">
                    <li><strong>Día:</strong> {{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</li>
                    <li><strong>Hora:</strong> {{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('h:i A') }}</li>
                    <li><strong>Profesional:</strong> {{ $reserva->employee?->user?->primer_nombre ?? 'Barbero' }} {{ $reserva->employee?->user?->primer_apellido ?? '' }}</li>
                    <li><strong>Estado:</strong> <span style="text-transform: uppercase; color: #D4AF37; font-weight: bold;">{{ $reserva->estado }}</span></li>
                    <li><strong>Total a Pagar:</strong> ${{ number_format($reserva->total, 0, ',', '.') }}</li>
                </ul>
            </div>

            @if($reserva->estado === 'confirmada')
                <p>Por favor recuerda llegar 5 minutos antes de tu turno programado.</p>
            @elseif($reserva->estado === 'cancelada')
                <p>Si deseas, puedes agendar una nueva cita desde tu portal en cualquier momento.</p>
            @endif
            
            <a href="{{ url('/mi-portal/mis-citas') }}" class="btn">Ver Mis Citas</a>
        </div>
        <div class="footer">
            <p>Este es un correo automático generado por el Sistema de Reservas JyM.</p>
            <p>&copy; {{ date('Y') }} Barbería JyM. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>
