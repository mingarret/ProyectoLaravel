@extends('layouts.app')
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">

@section('content')
<div class="custom-card p-4 mb-4">
    <h3 class="mb-4">Administración de Usuarios</h3>

    @can('manage-users') <!-- Verifica que el usuario tenga permiso para gestionar usuarios -->
        <!-- Mensaje de éxito -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Tabla de Usuarios -->
        <div class="card">
            <div class="card-header bg-dark text-white">
                <h5 class="mb-0">Lista de Usuarios</h5>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>Nombre</th>
                            <th>Email</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center">
                                    <!-- Botón para regenerar la contraseña -->
                                    <form action="{{ route('users.regenerate-password', $user->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('¿Seguro que quieres regenerar la contraseña de este usuario?')">
                                            Regenerar Contraseña
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">No hay usuarios registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        <!-- Mensaje para usuarios no autorizados -->
        <div class="alert alert-danger text-center" role="alert">
            No tienes permiso para acceder a esta sección.
        </div>
    @endcan
</div>
@endsection
