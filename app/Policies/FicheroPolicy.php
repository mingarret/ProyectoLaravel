<?php

namespace App\Policies;

use App\Models\Fichero;
use App\Models\User;

class FicheroPolicy
{
    // Política para subir archivos
    public function upload(User $user)
    {
        // Verifica si el usuario tiene permiso para subir archivos
        return $user->hasPermission('upload') || $user->can_upload;
    }

    // Política para eliminar (soft delete) archivos
    public function delete(User $user, Fichero $file)
    {
        // Permite la eliminación si el usuario tiene permiso o es el propietario del archivo
        return $user->hasPermission('delete') || $user->id === $file->user_id;
    }

    // Política para restaurar archivos eliminados
    public function restore(User $user, Fichero $file)
    {
        // Permite restaurar si el usuario tiene permiso o es el propietario del archivo
        return $user->hasPermission('restore') || $user->id === $file->user_id;
    }

    // Política para eliminar permanentemente archivos
    public function forceDelete(User $user, Fichero $file)
    {
        // Permite eliminar permanentemente si el usuario tiene el permiso específico
        return $user->hasPermission('forceDelete');
    }
}
