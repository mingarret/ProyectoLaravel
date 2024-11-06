<?php

namespace App\Http\Controllers;

use App\Models\Fichero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;

class FileController extends Controller
{
    // Método de subida de archivos
    public function upload(Request $request)
    {
        if (Gate::denies('upload', Fichero::class)) {
            abort(403, 'No tienes permiso para subir archivos.');
        }

        // Validación con mensajes personalizados
        $request->validate([
            'file' => 'required|file|max:5120|mimes:jpg,jpeg,png,pdf,docx',
        ], [
            'file.required' => 'Por favor, selecciona un archivo para subir.',
            'file.max' => 'El archivo no debe ser mayor a 5 MB.',
            'file.mimes' => 'Solo se permiten archivos de tipo JPG, PNG, PDF o DOCX.',
        ]);
        
        // Proceso de subida de archivo
        $file = $request->file('file');
        $path = $file->store('files'); // Guarda el archivo en storage/app/files

        // Creación del registro en la base de datos
        Fichero::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(), // Tamaño en bytes, puedes dividir por 1024 para KB
            'type' => $file->getMimeType(),
            'user_id' => Auth::id(), // Guarda el ID del usuario que sube el archivo
            'description' => $request->input('description'), // Guarda la descripción opcional
        ]);

        return back()->with('success', 'Archivo subido correctamente');
    }


    // Método para descargar archivos
    public function download(Fichero $file)
    {
        $user = auth()->user();

        // Verifica si el usuario es el usuario con ID 1 o tiene el rol de "administrador"
        if ($user->id !== 1 && !$user->hasRole('administrador')) {
            abort(403, 'No tienes permiso para descargar este archivo.');
        }

        return Storage::download($file->path, $file->name);
    }



    // Método de borrado (soft delete) de archivos
    public function delete(Fichero $file)
    {
        if (Gate::denies('delete', $file)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $file->delete(); // Marca el archivo como eliminado (soft delete)

        return back()->with('success', 'Archivo borrado correctamente');
    }

    // Método para restaurar archivos eliminados (soft deleted)
    public function restore($id)
    {
        $fichero = Fichero::onlyTrashed()->findOrFail($id); // Recupera solo archivos eliminados

        if (Gate::denies('restore', $fichero)) {
            abort(403, 'No tienes permiso para restaurar este archivo.');
        }

        $fichero->restore(); // Restaura el archivo

        return back()->with('success', 'Archivo restaurado correctamente');
    }

    // Método para eliminar completamente el archivo (permanente)
    public function forceDelete($id)
    {
        $fichero = Fichero::onlyTrashed()->findOrFail($id); // Recupera solo archivos eliminados

        if (Gate::denies('forceDelete', $fichero)) {
            abort(403, 'No tienes permiso para eliminar este archivo permanentemente.');
        }

        // Elimina el archivo del almacenamiento y borra el registro de la base de datos permanentemente
        Storage::delete($fichero->path);
        $fichero->forceDelete();

        return back()->with('success', 'Archivo eliminado permanentemente');
    }
}
