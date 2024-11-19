<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultados de la Búsqueda</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Resultados de la Búsqueda</h2>
        
        <!-- Botón para regresar a la página de bienvenida -->
        <div class="mb-3">
            <a href="{{ route('welcome') }}" class="btn btn-secondary">Volver a la página principal</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        
        @if($ficheros->isNotEmpty())
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Acción</th>
                        <th>Nombre</th>
                        <th>Descripción</th>
                        <th>Etiquetas</th>
                        <th>Autor</th>
                        <th>Tamaño</th>
                        <th>Propietario</th>
                        <th>Creado</th>
                        <th>Actualizado</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ficheros as $fichero)
                    <tr>
                        <td>
                            <a href="{{ route('files.preview', $fichero->id) }}" class="btn btn-info btn-sm">Previsualizar</a>
                            <a href="/download/{{ $fichero->id }}" class="btn btn-primary btn-sm">Descargar</a>
                        </td>
                        <td>{{ $fichero->name }}</td>
                        <td>{{ $fichero->description ?? 'N/A' }}</td>
                        <td>{{ $fichero->tags ?? 'N/A' }}</td>
                        <td>{{ $fichero->author ?? 'N/A' }}</td>
                        <td>{{ number_format($fichero->size / 1024, 2) }} KB</td>
                        <td>{{ $fichero->user->name }}</td>
                        <td>{{ $fichero->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $fichero->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-muted">No se encontraron archivos que coincidan con la búsqueda.</p>
        @endif
    </div>
</body>
</html>
