<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
{
    // Crear roles
    $adminRole = Role::create(['name' => 'admin']);
    $userRole = Role::create(['name' => 'user']);

    // Crear permisos
    $uploadPermission = Permission::create(['name' => 'upload']);
    $deletePermission = Permission::create(['name' => 'delete']);
    $restorePermission = Permission::create(['name' => 'restore']);
    $forceDeletePermission = Permission::create(['name' => 'forceDelete']);

    // Asignar permisos a roles
    $adminRole->permissions()->attach([$uploadPermission->id, $deletePermission->id, $restorePermission->id, $forceDeletePermission->id]);
    $userRole->permissions()->attach([$uploadPermission->id]);

    // Crear un usuario administrador y uno normal
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('admin')
    ]);
    $admin->roles()->attach($adminRole);

    $user = User::create([
        'name' => 'Regular User',
        'email' => 'user@example.com',
        'password' => Hash::make('user')
    ]);
    $user->roles()->attach($userRole);
}
}

