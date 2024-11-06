<?php

namespace App\Http\Controllers;

use App\Models\Fichero;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Archivos activos del usuario actual
        $ficheros = Fichero::where('user_id', auth()->id())->get();
        
        // Archivos eliminados (soft deleted) del usuario actual
        $ficherosEliminados = Fichero::onlyTrashed()->where('user_id', auth()->id())->get();

        // Pasar ambos conjuntos de datos a la vista
        return view('welcome', compact('ficheros', 'ficherosEliminados'));
    }
}
