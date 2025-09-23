<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Clientes extends Model
{
    protected $fillable = [
        'telefono',
        'alias',
    ];

    public function conversaciones(): HasMany
    {
        return $this->hasMany(conversacion::class, 'id_cliente');
    }
}
