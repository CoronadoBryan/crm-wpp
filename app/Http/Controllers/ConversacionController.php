<?php

namespace App\Http\Controllers;

use App\Models\Conversacion;
use App\Models\Clientes;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class ConversacionController extends Controller
{
    /**
     * Evalúa si el cliente necesita una nueva conversación y la crea si corresponde.
     */
    public function gestionarConversacion(Clientes $cliente, bool $esNuevo)
    {
        if ($esNuevo) {
            Log::info("🆕 Cliente nuevo, creando conversación...");
            return $this->crearConversacion($cliente);
        }

        $tieneActiva = $cliente->conversaciones()
            ->where('id_estado', '!=', 3) // 3 = finalizado
            ->exists();

        if (!$tieneActiva) {
            Log::info("🔄 Cliente sin conversación activa, creando nueva...");
            return $this->crearConversacion($cliente);
        }

        Log::info("📱 Cliente ya tiene conversación activa.");
        return null;
    }


    private function crearConversacion(Clientes $cliente)
    {
        try {
            $asesor = $this->seleccionarAsesorDisponible();

            if (!$asesor) {
                Log::warning("⚠️ No hay asesores disponibles.");
                return null;
            }

            $conversacion = Conversacion::create([
                'id_cliente' => $cliente->id,
                'id_usuario' => $asesor->id,
                'id_estado' => 1, // 1 = asignado
            ]);

            Log::info("💬 Conversación creada - Cliente: {$cliente->id}, Asesor: {$asesor->name}, Estado: {$conversacion->id_estado}");

            return $conversacion;

        } catch (\Exception $e) {
            Log::error("❌ Error creando conversación: " . $e->getMessage());
            return null;
        }
    }

    private function seleccionarAsesorDisponible()
    {
        $asesor = User::whereHas('roles', fn($q) => $q->where('name', 'asesor'))
            ->withCount(['conversaciones' => fn($q) => $q->where('id_estado', '!=', 3)])
            ->orderBy('conversaciones_count', 'asc')
            ->first();

        if (!$asesor) {
            Log::error("❌ No se encontraron asesores.");
        } else {
            Log::info("👤 Asesor seleccionado: {$asesor->name} (conversaciones activas: {$asesor->conversaciones_count})");
        }

        return $asesor;
    }

    /**
     * Cambia el estado de una conversación.
     */
    public function actualizarEstado(int $conversacionId, int $nuevoEstadoId)
    {
        try {
            $conversacion = Conversacion::findOrFail($conversacionId);
            $conversacion->update(['id_estado' => $nuevoEstadoId]);

            Log::info("🔄 Estado actualizado: {$conversacionId} -> estado {$nuevoEstadoId}");

            return $conversacion;

        } catch (\Exception $e) {
            Log::error("❌ Error actualizando estado: " . $e->getMessage());
            return null;
        }
    }

    public function finalizarConversacion(int $conversacionId)
    {
        return $this->actualizarEstado($conversacionId, 3);
    }

    public function ponerEnAtencion(int $conversacionId)
    {
        return $this->actualizarEstado($conversacionId, 2);
    }
}
