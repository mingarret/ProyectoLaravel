@extends('layouts.app')

@section('content')
<div class="container my-5">
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
    <a href="{{ route('files.download', $file->id) }}" class="btn btn-primary">Descargar Archivo</a>
    <a href="{{ route('wellcome') }}" class="btn btn-secondary">Volver a Inicio</a>
</div>
@endsection
