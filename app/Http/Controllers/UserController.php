<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    // Método para regenerar la contraseña de un usuario
    public function regeneratePassword(User $user)
    {
        // Genera una nueva contraseña aleatoria
        $newPassword = Str::random(8);

        // Actualiza la contraseña del usuario
        $user->password = Hash::make($newPassword);
        $user->save();

        // Puedes enviar la nueva contraseña al usuario por correo o mostrarla en la vista
        // Aquí asumimos que solo la mostramos como mensaje
        return redirect()->back()->with('success', "Nueva contraseña para {$user->name}: $newPassword");
    }

    public function index()
    {
        // Verificar si el usuario tiene permisos para gestionar usuarios
        if (Gate::denies('manage-users')) {
            abort(403, 'No tienes permiso para acceder a esta página.');
        }

        // Recupera y muestra los usuarios si la autorización es exitosa
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }


}
