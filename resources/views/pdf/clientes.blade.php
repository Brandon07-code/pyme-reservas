<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Reporte de Clientes - Barbería JyM</title>
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
        .badge { font-weight: bold; text-transform: uppercase; }
    </style>
</head>
<body>
    <div class="header-container">
        <h1 class="logo-text">Barbería JyM</h1>
        <p class="subtitle">Reporte de Clientes</p>
        <p style="font-size: 10px; margin: 0;">Generado el: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Teléfono</th>
                <th>Email</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @forelse($clients as $client)
                <tr>
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->primer_nombre }} {{ $client->primer_apellido }}</td>
                    <td>{{ $client->telefono }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->estado ? 'Activo' : 'Inactivo' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="text-align: center;">No se encontraron clientes.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">Sistema de Gestión PYME - Barbería y Perfumería JyM | Exportado mediante Laravel DomPDF</div>
</body>
</html>
