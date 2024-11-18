<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileVersion extends Model
{
    protected $fillable = ['file_id', 'path', 'version'];

    public function file()
    {
        return $this->belongsTo(Fichero::class);
    }
}
