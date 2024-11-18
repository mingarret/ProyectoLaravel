<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center" style="min-height: 100vh;">

<div class="container text-center my-5">
    @guest
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <h1 class="display-4 mb-4">Bienvenido a Nuestra Plataforma de Archivos</h1>
                <p class="lead text-muted mb-5">Guarda, organiza y gestiona tus archivos de forma segura y eficiente.</p>
                <a href="/login" class="btn btn-primary btn-lg">Iniciar Sesión</a>
            </div>
        </div>
    @endguest

    @auth
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h2 class="fw-bold">Bienvenido, {{ Auth::user()->name }}</h2>
            </div>
            <div>
                <a href="/logout" class="btn btn-outline-danger">Log out</a>
            </div>
            @if(auth()->user() && auth()->user()->hasRole('admin'))
                <div class="mb-5">
                    <a href="{{ route('admin.users') }}" class="btn btn-dark">Administrar Usuarios</a>
                </div>
            @endif
        </div>

        <div class="card mb-5 shadow-sm">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Buscar Archivos</h3>
                <form method="GET" action="{{ route('files.search') }}">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control" placeholder="Escribe palabras clave..." required>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                    <small class="form-text text-muted mt-2">
                        Puedes buscar por nombre, descripción, etiquetas o autor.
                    </small>
                </form>
            </div>
        </div>

        @can('upload', App\Models\Fichero::class)
            <div class="card mb-5">
                <div class="card-body text-center">
                    <h3 class="card-title text-center mb-4">Subir Archivos</h3>
                    <form method="POST" action="/upload" enctype="multipart/form-data" id="uploadForm">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="file" class="form-label">Selecciona un archivo (jpg, jpeg, png, pdf, docx, máx. 5 MB):</label>
                            <input type="file" name="file" id="file" required class="form-control">
                        </div>
                        <div class="form-group mb-3">
                            <label for="description" class="form-label">Descripción (opcional):</label>
                            <textarea name="description" id="description" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="form-group mb-3">
                            <label for="tags" class="form-label">Etiquetas (opcional):</label>
                            <input type="text" name="tags" id="tags" class="form-control" placeholder="Ejemplo: proyecto, importante">
                        </div>
                        <div class="form-group mb-3">
                            <label for="author" class="form-label">Autor (opcional):</label>
                            <input type="text" name="author" id="author" class="form-control" placeholder="Nombre del autor">
                        </div>
                        <button type="submit" class="btn btn-success mt-3">+ Añadir Archivo</button>
                    </form>
                </div>
            </div>
        @endcan

        @if(session('success'))
            <div class="alert alert-success mb-5">{{ session('success') }}</div>
        @elseif(session('error'))
            <div class="alert alert-danger mb-5">{{ session('error') }}</div>
        @endif

        <div class="my-5">
            @if($archivosCompartidos->isNotEmpty())
                <div class="card mt-3 mb-5">
                    <div class="card-header bg-info text-white">
                        <h4 class="mb-0">Archivos Compartidos</h4>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Acción</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Etiquetas</th>
                                    <th>Autor</th>
                                    <th>Tamaño</th>
                                    <th>Compartido por</th>
                                    <th>Compartido el</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($archivosCompartidos as $archivo)
                                    <tr>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('files.preview', $archivo->id) }}" class="btn btn-info btn-sm">Previsualizar</a>
                                                <a href="/download/{{ $archivo->id }}" class="btn btn-primary btn-sm">Descargar</a>
                                            </div>
                                        </td>
                                        <td>{{ $archivo->name }}</td>
                                        <td>{{ $archivo->description ?? 'N/A' }}</td>
                                        <td>{{ $archivo->tags ?? 'N/A' }}</td>
                                        <td>{{ $archivo->author ?? 'N/A' }}</td>
                                        <td>{{ number_format($archivo->size / 1024, 2) }} KB</td>
                                        <td>{{ $archivo->user->name }}</td>
                                        <td>{{ $archivo->created_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <p class="text-center text-muted mt-3">No hay archivos compartidos.</p>
            @endif
        </div>

    </br>

        <div class="my-5">
            <div class="card-header bg-dark text-white">
                <h4 class="mb-0">Archivos Activos</h4>
            </div>
            @if($ficheros->isNotEmpty())
                <table class="table table-bordered table-striped mb-5">
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
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('files.preview', $fichero->id) }}" class="btn btn-info btn-sm">Previsualizar</a>
                                        <a href="/download/{{ $fichero->id }}" class="btn btn-primary btn-sm">Descargar</a>
                                        <a href="{{ route('files.editMetadata', $fichero->id) }}" class="btn btn-warning btn-sm">Editar</a>
                                        <a href="/delete/{{ $fichero->id }}" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')">Borrar</a>
                                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#shareModal-{{ $fichero->id }}">Compartir</button>
                                    </div>
                                </td>
                                <td>{{ $fichero->name }}</td>
                                <td>{{ $fichero->description }}</td>
                                <td>{{ $fichero->tags }}</td>
                                <td>{{ $fichero->author }}</td>
                                <td>{{ number_format($fichero->size / 1024, 2) }} KB</td>
                                <td>{{ $fichero->user->name }}</td>
                                <td>{{ $fichero->created_at->format('d/m/Y H:i') }}</td>
                                <td>{{ $fichero->updated_at->format('d/m/Y H:i') }}</td>
                            </tr>
        
                            <!-- Modal de Compartir -->
                            <div class="modal fade" id="shareModal-{{ $fichero->id }}" tabindex="-1" aria-labelledby="shareModalLabel-{{ $fichero->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form method="POST" action="{{ route('files.share', $fichero->id) }}">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="shareModalLabel-{{ $fichero->id }}">Compartir Archivo</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <label for="user_id" class="form-label">Selecciona el usuario:</label>
                                                <select name="user_id" class="form-select" required>
                                                    @foreach ($users as $user)
                                                        @if ($user->id !== Auth::id())
                                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                                <button type="submit" class="btn btn-primary">Compartir</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center text-muted">No hay archivos disponibles.</p>
            @endif
        </div>

    </br>
        
        <div class="my-5">
            @if($ficherosEliminados->isNotEmpty())
                <div class="card mt-3">
                    <div class="card-header bg-warning">
                        <h4 class="mb-0">Archivos Eliminados</h4>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered table-striped mb-0">
                            <thead class="table-warning">
                                <tr>
                                    <th>Acción</th>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Etiquetas</th>
                                    <th>Autor</th>
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
                                                <a href="{{ route('files.restore', $fichero->id) }}" class="btn btn-warning btn-sm">Restaurar</a>
                                            @endcan
                                            @can('forceDelete', $fichero)
                                                <a href="{{ route('files.forceDelete', $fichero->id) }}" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo permanentemente?')">Eliminar Permanente</a>
                                            @endcan
                                        </td>
                                        <td>{{ $fichero->name }}</td>
                                        <td>{{ $fichero->description ?? 'N/A' }}</td>
                                        <td>{{ $fichero->tags ?? 'N/A' }}</td>
                                        <td>{{ $fichero->author ?? 'N/A' }}</td>
                                        <td>{{ number_format($fichero->size / 1024, 2) }} KB</td>
                                        <td>{{ $fichero->user->name }}</td>
                                        <td>{{ $fichero->deleted_at->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <p class="text-center text-muted mt-3">No hay archivos eliminados.</p>
            @endif
        </div>

    @endauth
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
