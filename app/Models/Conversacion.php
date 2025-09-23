<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class conversacion extends Model
{
    protected $table = 'conversacions';

    protected $fillable = [
        'id_usuario',
        'id_cliente',
        'id_estado',
    ];

    // Relación con Usuario
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    // Relación con Cliente
    public function cliente(): BelongsTo
    {
        return $this->belongsTo(Clientes::class, 'id_cliente');
    }

    // Relación con Estado de Conversación
    public function estado(): BelongsTo
    {
        return $this->belongsTo(estado_conversacion::class, 'id_estado');
    }

}
