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
        // Lógica para mostrar la lista de usuarios
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create'); // Asegúrate de que exista esta vista
    }

    //Metodo para la creacion y validacion de un usuario
    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string', // Añade este campo si asignas un rol en la creación
        ]);

        // Creación del usuario
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
            'role' => $request->input('role'), // Asignación del rol
        ]);

        // Redirige al listado de usuarios con un mensaje de éxito
        return redirect()->route('admin.users')->with('success', 'Usuario creado exitosamente.');
    }

    //Metodo para editar usuarios
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    //Metodo para actualizar los datos del usuario
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|string',
        ]);

        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'role' => $request->input('role'),
        ]);
        return redirect()->route('admin.users')->with('success', 'Usuario actualizado correctamente.');
    }

    //Metodo para eliminar usuario
    public function destroy($id)
    {
        // Encuentra el usuario por ID y elimínalo
        $user = User::findOrFail($id);
        $user->delete();
        // Redirige de vuelta al listado de usuarios con un mensaje de éxito
        return redirect()->route('admin.users')->with('success', 'Usuario eliminado correctamente.');
    }
}
