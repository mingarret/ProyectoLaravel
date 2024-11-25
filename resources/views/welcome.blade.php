@extends('layouts.app')


@section('title', 'Bienvenido')
@section('content')
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">

<div class="container text-center my-5">
    @guest
        <div class="row justify-content-center mb-5">
            <div class="col-md-8">
                <!-- Título de la aplicación con efecto de animación -->
                <h1 class="app-title mb-4">FAK-FILES</h1>
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
                <div>
                    <a href="{{ route('admin.users') }}" class="btn btn-dark">Administrar Usuarios</a>
                </div>
            @endif
        </div>

        {{-- Div para Reportes --}}
        <div class="custom-card p-4 mb-4">
            <h2 class="mb-3 text-center">Archivos Disponibles</h2>
            <ul class="list-group">
                @foreach($files as $file)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $file->name }}</span>
                        <a href="{{ route('report.fileActivity', ['file' => $file->id]) }}" class="btn btn-sm btn-outline-primary">Reporte de Actividad</a>
                    </li>
                @endforeach
            </ul>
            <div class="mt-4 text-center">
                <a href="{{ route('report.usageStatistics') }}" class="btn btn-primary">Ver Estadísticas de Uso</a>
            </div>
        </div>
        
        {{-- Div para Buscar Archivos --}}
        <div class="custom-card p-4 mb-4">
            <div class="card-body">
                <h3 class="card-title text-center mb-4">Buscar Archivos</h3>
                <form method="GET" action="{{ route('files.search') }}">
                    <div class="input-group">
                        <input type="text" name="query" class="form-control" placeholder="Escribe palabras clave..." required>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search custom-icon-size"></i> Buscar
                        </button>
                    </div>
                    <small class="form-text text-muted mt-2">
                        Puedes buscar por nombre, descripción, etiquetas o autor.
                    </small>
                </form>
            </div>
        </div>

        @can('upload', App\Models\Fichero::class)
            <div class="custom-card p-4 mb-4">
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

        {{-- Mensaje de éxito con temporizador --}}
        @if(session('success'))
            <div class="alert alert-success" id="success-alert">{{ session('success') }}</div>
        @endif

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    setTimeout(() => {
                        alert.style.transition = 'opacity 0.5s';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    }, 5000); // 5000 ms = 5 segundos
                }
            });
        </script>

        {{-- Div para Archivos Compartidos --}}
        <div class="custom-card p-4 mb-4">
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
                                                <a href="{{ route('files.preview', $archivo->id) }}" class="btn btn-info btn-sm" title="Previsualizar">
                                                    <i class="fas fa-eye custom-icon-size"></i>
                                                </a>
                                                <a href="/download/{{ $archivo->id }}" class="btn btn-primary btn-sm" title="Descargar">
                                                    <i class="fas fa-download custom-icon-size"></i>
                                                </a>
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
        </tr>
        {{-- Div para los Archivos Activos --}}
        {{-- <div class="custom-card p-4 mb-4"> --}}
            <div class="card-header bg-dark text-white text-center" style="width: 100%;">
                <h4 class="mb-0">Archivos Activos</h4>
            </div>
            <div class="card-body p-0" style="overflow-x: auto;">
                @if($ficheros->isNotEmpty())
                    <table class="table table-bordered table-striped mb-0 w-100">
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
                                        <!-- Botones con iconos -->
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('files.preview', $fichero->id) }}" class="btn btn-info btn-sm" title="Previsualizar">
                                                <i class="fas fa-eye"></i> <!-- Icono de ojo para "Previsualizar" -->
                                            </a>
                                            <a href="/download/{{ $fichero->id }}" class="btn btn-primary btn-sm" title="Descargar">
                                                <i class="fas fa-download custom-icon-size"></i> <!-- Icono de descarga -->
                                            </a>
                                            <a href="{{ route('files.editMetadata', $fichero->id) }}" class="btn btn-warning btn-sm" title="Editar">
                                                <i class="fas fa-edit custom-icon-size"></i> <!-- Icono de lápiz para "Editar" -->
                                            </a>
                                            <a href="/delete/{{ $fichero->id }}" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo?')" title="Borrar">
                                                <i class="fas fa-trash custom-icon-size"></i> <!-- Icono de papelera para "Borrar" -->
                                            </a>
                                            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#shareModal-{{ $fichero->id }}" title="Compartir">
                                                <i class="fas fa-share-alt custom-icon-size"></i> <!-- Icono de compartir -->
                                            </button>
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
                    <p class="text-center text-muted mt-3">No hay archivos disponibles.</p>
                @endif
            </div>
        {{-- </div> --}}
        </tr>
        {{-- Div para los Archivos Eliminados --}}
        <div class="custom-card p-4 mb-4">
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
                                                <a href="{{ route('files.restore', $fichero->id) }}" class="btn btn-warning btn-sm" title="Restaurar">
                                                    <i class="fas fa-undo custom-icon-size"></i>
                                                </a>
                                            @endcan
                                            @can('forceDelete', $fichero)
                                                <a href="{{ route('files.forceDelete', $fichero->id) }}" class="btn btn-danger btn-sm" title="Eliminar Permanentemente" onclick="return confirm('¿Estás seguro de que deseas eliminar este archivo permanentemente?')">
                                                    <i class="fas fa-trash-alt custom-icon-size"></i>
                                                </a>
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
@endsection

<style>
    .app-title {
        font-size: 3rem;
        font-weight: bold;
        background: linear-gradient(90deg, #00bfff, #ffffff);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradientMove 3s infinite linear;
    }

    @keyframes gradientMove {
        0% { background-position: 0% 50%; }
        50% { background-position: 100% 50%; }
        100% { background-position: 0% 50%; }
    }

    .custom-card {
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    border: 1px solid #ddd;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .custom-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
    }

</style>
