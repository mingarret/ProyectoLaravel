<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    // Relación muchos a muchos con usuarios
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // Relación muchos a muchos con roles
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }
}
