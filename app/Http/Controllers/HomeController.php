<?php

namespace App\Http\Controllers;

use App\Models\Fichero;
use Illuminate\Http\Request;
use App\Models\User;


class HomeController extends Controller
{
    public function index()
    {
        // Archivos activos del usuario actual
        $ficheros = Fichero::where('user_id', auth()->id())->get();
        
        // Archivos eliminados (soft deleted) del usuario actual
        $ficherosEliminados = Fichero::onlyTrashed()->where('user_id', auth()->id())->get();

        //dd($ficherosEliminados); // Imprime los archivos eliminados para verificar que se estén recuperando correctamente
        //dd($ficherosEliminados); // Verifica si hay datos o si está vacío

        // Obtener los archivos compartidos con el usuario actual
        $archivosCompartidos = Fichero::whereHas('sharedWith', function ($query) {
            $query->where('user_id', auth()->id());
        })->get();

        $users = User::all(); // Lista de usuarios para compartir archivos

        //dd($archivosCompartidos);
        
        // Pasar ambos conjuntos de datos a la vista
        return view('welcome', compact('ficheros', 'ficherosEliminados', 'archivosCompartidos', 'users'));
    }
}
