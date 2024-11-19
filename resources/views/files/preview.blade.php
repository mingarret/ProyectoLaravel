@extends('layouts.app')
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">

@section('content')
<div class="custom-card p-4 mb-4">
    <h3>Vista Previa: {{ $file->name }}</h3>
    <div class="mb-3">
        @if (str_contains($file->type, 'image'))
            <img src="{{ $fileUrl }}" class="img-fluid" alt="{{ $file->name }}">
        @elseif ($file->type === 'application/pdf')
        <iframe src="{{ asset($file->path) }}" width="100%" height="600px"></iframe>
        @elseif ($file->type === 'text/plain')
            <pre>{{ file_get_contents(storage_path('app/' . $file->path)) }}</pre>
        @else
            <p>No se puede mostrar una vista previa para este tipo de archivo.</p>
        @endif
    </div>
    <a href="/download/{{ $file->id }}" class="btn btn-primary btn-sm" title="Descargar">
        <i class="fas fa-download custom-icon-size"></i> <!-- Icono de descarga -->
    </a>
    <a href="{{ route('welcome') }}" class="btn btn-secondary">Volver a Inicio</a>
</div>
@endsection
