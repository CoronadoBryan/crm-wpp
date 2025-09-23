<?php

namespace App\Http\Controllers;

use App\Models\Clientes;
use Illuminate\Support\Facades\Log;

class ClienteController extends Controller
{

    public function buscarOCrearCliente($telefono, $alias = null)
    {
        try {
            $cliente = Clientes::firstOrCreate(
                ['telefono' => $telefono],
                ['alias' => $alias]
            );
            
            $esNuevo = $cliente->wasRecentlyCreated;
            $accion = $esNuevo ? 'creado' : 'encontrado';
            
            Log::info("👤 Cliente {$accion}: {$cliente->telefono} - {$cliente->alias}");
            
            return [
                'cliente' => $cliente,
                'es_nuevo' => $esNuevo
            ];
            
        } catch (\Exception $e) {
            Log::error("❌ Error procesando cliente {$telefono}: " . $e->getMessage());
            return null;
        }
    }
}
