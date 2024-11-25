<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Fichero extends Model
{
    use SoftDeletes; // Con esto, los archivos eliminados se marcarán con una fecha en deleted_at

    protected $fillable = [
        'name', 'path', 'size', 'type', 'user_id', 'description', 'tags', 'author'
    ];

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

    // Método para la relación de archivos compartidos
    public function sharedWith(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'file_shares', 'fichero_id', 'user_id');
    }

    //Metodo para la actividad de los ficheros
    public function activities()
    {
        return $this->hasMany(FileActivity::class, 'file_id');
    }

}
