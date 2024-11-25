<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Actividad</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="custom-card p-4 mb-4">
            <h1 class="text-center mb-4">Reporte de Actividad del Archivo</h1>
            @if($activities->isEmpty())
                <p class="text-center text-muted">No hay actividades registradas para este archivo.</p>
            @else
                <table class="table table-bordered table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Acción</th>
                            <th>Usuario</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($activities as $activity)
                            <tr>
                                <td>{{ ucfirst($activity->action) }}</td>
                                <td>{{ $activity->user ? $activity->user->name : 'Anónimo' }}</td>
                                <td>{{ $activity->performed_at }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <div class="text-center mt-4">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

