<?php

namespace App\Http\Controllers;

use App\Models\Fichero;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class FileController extends Controller
{
    // Método de subida de archivos
    public function upload(Request $request)
    {
        // Validación de archivo
        $request->validate([
            'file' => 'required|file|max:5120|mimes:jpg,jpeg,png,pdf,docx,txt',
        ], [
            'file.required' => 'Por favor, selecciona un archivo para subir.',
            'file.max' => 'El archivo no debe ser mayor a 5 MB.',
            'file.mimes' => 'Solo se permiten archivos de tipo JPG, PNG, PDF, DOCX o TXT.',
        ]);

        // Asignar el archivo subido a una variable
        $file = $request->file('file');

        // Subir archivo
        $path = $file->store();

        // Guardar información del archivo en la base de datos
        Fichero::create([
            'name' => $file->getClientOriginalName(),
            'path' => $path,
            'size' => $file->getSize(),
            'type' => $file->getMimeType(),
            'user_id' => Auth::id(),
            'description' => $request->input('description'),
        ]);

        return back()->with('success', 'Archivo subido correctamente');
    }

    // Método para descargar archivos
    public function download(Fichero $file)
    {
        return Storage::download($file->path, $file->name);
    }

    // Método de borrado (soft delete) de archivos
    public function delete(Fichero $file)
    {
        if (Gate::denies('delete', $file)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $file->delete();

        return back()->with('success', 'Archivo borrado correctamente');
    }

    // Método para restaurar archivos eliminados (soft deleted)
    public function restore($id)
    {
        $fichero = Fichero::onlyTrashed()->findOrFail($id);

        if (Gate::denies('restore', $fichero)) {
            abort(403, 'No tienes permiso para restaurar este archivo.');
        }

        $fichero->restore();

        return back()->with('success', 'Archivo restaurado correctamente');
    }

    // Método para eliminar completamente el archivo (permanente)
    public function forceDelete($id)
    {
        $fichero = Fichero::onlyTrashed()->findOrFail($id);

        if (Gate::denies('forceDelete', $fichero)) {
            abort(403, 'No tienes permiso para eliminar este archivo permanentemente.');
        }

        Storage::delete($fichero->path);
        $fichero->forceDelete();

        return back()->with('success', 'Archivo eliminado permanentemente');
    }

    // Método para visualizar documentos PDF sin tener que descargarlos
    public function preview(Fichero $file)
    {
        $supportedTypes = [
            'application/pdf',
            'image/jpeg',
            'image/png',
            'text/plain',
        ];
    
        if (in_array($file->type, $supportedTypes)) {
            $fileUrl = Storage::url($file->path);
            return view('files.preview', compact('fileUrl', 'file'));
        }
    
        abort(415, 'El formato de archivo no es compatible para vista previa.');
    }

    // Método para transmitir el archivo para vista previa en un iframe
    public function stream(Fichero $file)
    {
        if (!file_exists(storage_path('app/' . $file->path))) {
            abort(404, 'El archivo no se encuentra.');
        }
    
        return response()->file(storage_path('app/' . $file->path));
    }

    // Método para mostrar la vista de edición de metadatos
    public function showEditMetadataForm($id)
    {
        $file = Fichero::findOrFail($id);
        return view('files.edit.editMetadata', compact('file'));
    }

    // Método para actualizar los metadatos del archivo en la BBDD
    public function updateMetadata(Request $request, $id)
    {
        $fichero = Fichero::findOrFail($id);

        $request->validate([
            'description' => 'nullable|string|max:255',
            'tags' => 'nullable|string|max:255',
            'author' => 'nullable|string|max:255',
        ]);

        $fichero->update([
            'description' => $request->input('description'),
            'tags' => $request->input('tags'),
            'author' => $request->input('author'),
        ]);

        return redirect()->route('welcome')->with('success', 'Metadatos actualizados correctamente.');
    }

    // Método para compartir archivos
    public function share(Request $request, $id)
    {
        $fichero = Fichero::findOrFail($id); // Verifica si se obtiene correctamente el archivo
        
        if (!$fichero) {
            return redirect()->back()->with('error', 'Archivo no encontrado.');
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Añadir el registro en la tabla 'file_shares' para compartir el archivo
        $fichero->sharedWith()->attach($validated['user_id']);

        return redirect()->route('welcome')->with('success', 'Archivo compartido con éxito.');
    }

    //Metodo para la busqueda de archivos
    public function search(Request $request)
{
    $query = $request->input('query');
    
    $ficheros = Fichero::where(function ($q) use ($query) {
                        $q->where('user_id', Auth::id())
                          ->orWhereHas('sharedWith', function ($sharedQuery) {
                              $sharedQuery->where('user_id', Auth::id());
                          });
                    })
                    ->where(function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%")
                          ->orWhere('description', 'LIKE', "%{$query}%")
                          ->orWhere('tags', 'LIKE', "%{$query}%")
                          ->orWhere('author', 'LIKE', "%{$query}%");
                    })
                    ->get();

    return view('searchResults', compact('ficheros'))->with('success', 'Resultados de la búsqueda');
}



}
