@extends('layouts.app')
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">

@section('content')
<div class="custom-card p-4 mb-4">
    <h2>Crear Nuevo Usuario</h2>
    <form action="{{ route('admin.users.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="role">Rol</label>
            <input type="text" name="role" id="role" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary mt-3">Crear Usuario</button>
        <a href="{{ route('admin.users') }}" class="btn btn-outline-secondary mt-3">Volver a la Lista de Usuarios</a>
        <a href="{{ route('welcome') }}" class="btn btn-outline-primary mt-3">Volver al Inicio</a>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

    </form>
</div>
@endsection
