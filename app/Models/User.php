<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Relación muchos a muchos con roles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    // Relación muchos a muchos con permisos
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    // Método para verificar si el usuario tiene un rol específico
    public function hasRole($roleName)
    {
        return $this->roles->contains('name', $roleName);
    }

    // Método para verificar si el usuario tiene un permiso específico
    public function hasPermission($permissionName)
    {
        return $this->permissions->contains('name', $permissionName) ||
               $this->roles->pluck('permissions')->flatten()->contains('name', $permissionName);
    }

}

