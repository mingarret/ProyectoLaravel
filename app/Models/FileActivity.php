<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileActivity extends Model
{
    protected $fillable = ['file_id', 'user_id', 'action', 'performed_at'];

    public function file()
    {
        return $this->belongsTo(Fichero::class, 'file_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

