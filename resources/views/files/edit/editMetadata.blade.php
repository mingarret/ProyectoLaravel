@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h2 class="mb-0">Editar Metadatos del Archivo</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('files.updateMetadata', $file->id) }}" method="POST">
                @csrf
                <div class="form-group mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea name="description" id="description" class="form-control" rows="3">{{ old('description', $file->description) }}</textarea>
                </div>
                <div class="form-group mb-3">
                    <label for="tags" class="form-label">Etiquetas (Separadas por comas)</label>
                    <input type="text" name="tags" id="tags" class="form-control" value="{{ old('tags', $file->tags) }}">
                </div>
                <div class="form-group mb-3">
                    <label for="author" class="form-label">Autor</label>
                    <input type="text" name="author" id="author" class="form-control" value="{{ old('author', $file->author) }}">
                </div>
                <!-- Agrega más campos si es necesario -->
                <button type="submit" class="btn btn-success mt-3">Guardar Cambios</button>
            </form>
            <a href="{{ route('welcome') }}" class="btn btn-outline-secondary mt-3">Volver al Inicio</a>
        </div>
    </div>
</div>
@endsection
