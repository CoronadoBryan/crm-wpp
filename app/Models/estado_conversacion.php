<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado_conversacion extends Model
{
    protected $fillable = [
        'nombre',
        'descripcion', 
        'color',
        'activo'
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];
}
