<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Fichero extends Model
{
    use SoftDeletes; //Con esto, los archivos eliminados se marcarán con una fecha en deleted_at
    // en lugar de ser borrados físicamente de la base de datos.

    protected $fillable = ['name', 'path', 'user_id', 'size', 'type', 'description'];

    public function getsize()
    {
        return Storage::size($this->path);
    }

    public function getUrlAttribute()
    {
        return Storage::url($this->path);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
