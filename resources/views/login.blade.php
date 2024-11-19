@extends('layouts.app')

@section('title', 'Iniciar Sesión')
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
@section('content')
<div class="p-4 mb-4">
    <!-- Mensajes de error -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Formulario de Inicio de Sesión -->
    <div class="row justify-content-center custom-card p-4 mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h5>Iniciar Sesión</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="/login">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">Correo Electrónico:</label>
                            <input type="email" name="email" class="form-control" placeholder="Email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña:</label>
                            <input type="password" name="password" class="form-control" placeholder="Contraseña" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Iniciar Sesión</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
