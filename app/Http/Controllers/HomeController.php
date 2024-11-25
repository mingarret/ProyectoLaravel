<?php

namespace App\Http\Controllers;

use App\Models\Fichero;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
{
    // Archivos activos del usuario actual
    $ficheros = Fichero::where('user_id',  Auth::id())->get();

    // Archivos eliminados (soft deleted) del usuario actual
    $ficherosEliminados = Fichero::onlyTrashed()->where('user_id',  Auth::id())->get();

    // Obtener los archivos compartidos con el usuario actual
    $archivosCompartidos = Fichero::whereHas('sharedWith', function ($query) {
        $query->where('user_id', Auth::id());
    })->get();

    // Obtener todos los archivos si es necesario
    $files = Fichero::all(); // Filtrar si es necesario

    $users = User::all(); // Lista de usuarios para compartir archivos

    // Pasar todos los conjuntos de datos a la vista
    return view('welcome', compact('ficheros', 'ficherosEliminados', 'archivosCompartidos', 'users', 'files'));
}

}
