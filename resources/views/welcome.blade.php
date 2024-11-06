<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container text-center">
    <!-- Vista de bienvenida para usuarios no autenticados -->
    @guest
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h1 class="display-4 mb-4">Bienvenido a Nuestra Plataforma de Archivos</h1>
                <p class="lead text-muted mb-5">Guarda, organiza y gestiona tus archivos de forma segura y eficiente.</p>
                
                <a href="/login" class="btn btn-primary btn-lg">Iniciar Sesión</a>
            </div>
        </div>
    @endguest

    <!-- Vista completa para usuarios autenticados -->
    @auth
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h5>Bienvenido, {{ Auth::user()->name }}</h5>
            </div>
            <div>
                <a href="/logout" class="btn btn-outline-danger">Log out</a>
            </div>
        </div>

        <!-- Botón de administración solo para administradores -->
        @if (Auth::user()->hasRole('admin'))
            <div class="mb-4">
                <a href="{{ route('admin.users') }}" class="btn btn-dark">Administrar Usuarios</a>
            </div>
        @endif

        <!-- Botón para Añadir Archivo -->
        @can('upload', App\Models\Fichero::class)
            <div class="card mb-4">
                <div class="card-body text-center">
                    <form method="POST" action="/upload" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="form-group">
                            <label for="file">Selecciona un archivo (jpg, jpeg, png, pdf, docx, máx. 5 MB):</label>
                            <input type="file" name="file" id="file" required class="form-control">
                        </div>
                        
                        <div class="form-group mt-2">
                            <label for="description">Descripción (opcional):</label>
                            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-success mt-3">+ Añadir Archivo</button>
                    </form>
                </div>
            </div>
        @endcan

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Tabla de Archivos Activos -->
        <h5>Archivos Activos</h5>
        @if($ficheros->isNotEmpty())
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Acción</th>
                        <th>Nombre</th>
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
                            <a href="/download/{{ $fichero->id }}" class="btn btn-primary btn-sm">Descargar</a>
                            <a href="/delete/{{ $fichero->id }}" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')">Borrar</a>
                        </td>
                        <td>{{ $fichero->name }}</td>
                        <td>{{ number_format($fichero->size / 1024, 2) }} KB</td>
                        <td>{{ $fichero->user->name }}</td>
                        <td>{{ $fichero->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $fichero->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-muted">No hay archivos disponibles.</p>
        @endif

        <!-- Tabla de Archivos Eliminados (Soft Deleted) -->
        <h5 class="mt-5">Archivos Eliminados</h5>
        @if($ficherosEliminados->isNotEmpty())
            <table class="table table-bordered table-striped">
                <thead class="table-warning">
                    <tr>
                        <th>Acción</th>
                        <th>Nombre</th>
                        <th>Tamaño</th>
                        <th>Propietario</th>
                        <th>Eliminado el</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ficherosEliminados as $fichero)
                    <tr>
                        <td>
                            @can('restore', $fichero)
                                <a href="/restore/{{ $fichero->id }}" class="btn btn-warning btn-sm">Restaurar</a>
                            @endcan
                            @can('forceDelete', $fichero)
                                <a href="/force-delete/{{ $fichero->id }}" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo permanentemente?')">Eliminar Permanente</a>
                            @endcan
                        </td>
                        <td>{{ $fichero->name }}</td>
                        <td>{{ number_format($fichero->size / 1024, 2) }} KB</td>
                        <td>{{ $fichero->user->name }}</td>
                        <td>{{ $fichero->deleted_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-center text-muted">No hay archivos eliminados.</p>
        @endif
    @endauth
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
