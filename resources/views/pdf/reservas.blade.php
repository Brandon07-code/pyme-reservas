<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Citas - Barbería JyM</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }
        .header-container { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #D4AF37; padding-bottom: 10px; }
        .logo-text { font-size: 24px; font-weight: bold; color: #000; text-transform: uppercase; margin: 0; }
        .subtitle { color: #666; font-size: 12px; margin-top: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th { background-color: #0f172a; color: #D4AF37; padding: 10px; text-align: left; font-size: 11px; text-transform: uppercase; }
        td { border: 1px solid #ddd; padding: 8px; }
        tr:nth-child(even) { background-color: #f8fafc; }
        .footer { text-align: center; margin-top: 30px; font-size: 9px; color: #888; border-top: 1px solid #ddd; padding-top: 10px; }
        .status-badge { font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header-container">
        <h1 class="logo-text">Barbería JyM</h1>
        <p class="subtitle">Reporte Oficial de Citas Agendadas</p>
        <p style="font-size: 10px; margin: 0;">Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Cliente</th>
                <th>Barbero Asignado</th>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($reservations as $reserva)
                <tr>
                    <td>#{{ str_pad($reserva->id, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $reserva->client->user->primer_nombre ?? $reserva->client->primer_nombre }} {{ $reserva->client->user->primer_apellido ?? $reserva->client->primer_apellido }}</td>
                    <td>{{ $reserva->employee->user->primer_nombre ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->fecha)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($reserva->hora_inicio)->format('H:i') }}</td>
                    <td>${{ number_format($reserva->total, 0, ',', '.') }}</td>
                    <td class="status-badge">{{ $reserva->estado }}</td>
                </tr>
            @empty
                <tr><td colspan="7" style="text-align:center; padding: 20px;">No se encontraron citas con los filtros aplicados.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Sistema de Gestión PYME - Barbería y Perfumería JyM | Exportado mediante Laravel DomPDF
    </div>
</body>
</html>
